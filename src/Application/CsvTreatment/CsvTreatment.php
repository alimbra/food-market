<?php

declare(strict_types=1);

namespace App\Application\CsvTreatment;

use App\Domain\Data\DomainEntityManagerInterface;
use App\Domain\Data\RegistryImportFileSaveInterface;
use App\Domain\Data\RegistryProductInterface;
use App\Domain\Data\RegistrySupplierInterface;
use App\Domain\Dto\ValueObjectProduct;
use App\Domain\Entity\ImportFile;
use App\Domain\Entity\Product;
use App\Domain\Entity\Supplier;
use App\Domain\Mailer\CustomMailerInterface;
use App\Domain\StatusFileUpdated\UpdateFileStateInterface;
use App\Domain\Utils\CsvReader;
use App\Domain\Utils\MpEncoder;
use App\Infrastructure\Message\FileMessage;
use Exception;
use Psr\Log\LoggerInterface;
use Throwable;

class CsvTreatment
{
    public function __construct(
        protected LoggerInterface $logger,
        protected DomainEntityManagerInterface $domainEntityManager,
        protected RegistryProductInterface $registryProduct,
        protected RegistrySupplierInterface $registrySupplier,
        protected RegistryImportFileSaveInterface $registryImportFileSave,
        protected CustomMailerInterface $custumMailer,
        protected UpdateFileStateInterface $updateFilesState,
    ) {
    }

    /**
     * @throws Exception
     */
    public function treatmentFile(FileMessage $fileMessage): void
    {
        $fullpath = $fileMessage->getFileDto()->getFilePath();
        $array = explode('/', $fullpath);
        $savedName = end($array);

        $import = $this->registryImportFileSave->findOneBy(['originalFilename' => $savedName]);
        if (!$import instanceof ImportFile) {
            throw new Exception('Import not found');
        }

        try {
            $this->processImport($fileMessage);
            $this->updateFilesState->successImport($import);
        } catch (Throwable $e) {
            $this->logger->error($e, [Throwable::class => $e::class]);
            $this->updateFilesState->failureImport($import);
        }
    }

    /**
     * @throws Throwable
     */
    public function processImport(FileMessage $fileMessage): void
    {
        $batchSize = 20;

        $i = 1;
        $handle = CSVReader::openFile($fileMessage->getFileDto());
        $supplier = $this->registrySupplier->findOneBy(['id' => $fileMessage->getSupplierId()]);

        if (!$supplier instanceof Supplier) {
            throw new Exception('Supplier not found');
        }

        $results = [];
        while ($line = CSVReader::readNextLine($handle)) {
            $lineContent = array_map(static fn(string $currentLine) => MpEncoder::utf8Encode($currentLine), $line);
            $results[] = $lineContent;
            ++$i;
            if (($i % $batchSize) === 0) {
                $this->processBulk($results, $supplier);
                $results = [];
            }
        }

        $this->processBulk($results, $supplier);

        $this->custumMailer->sendMail();
        CSVReader::closeFile($handle);
    }


    private function processBulk(array $results, Supplier $supplier): void
    {
        foreach ($results as $productLine) {
            $vo = ValueObjectProduct::fromArray($productLine);
            if ($vo) {
                //we can use Design pattern strategy here but since we only two conditions, it is not that necessary
                $product = $this->registryProduct->findOneBy(['code' => $vo->getCode()]);
                if ($product === null) {
                    $product = new Product();
                    $product->setCode($vo->getCode());
                    $product->setName($vo->getName());
                    $product->setPrice($vo->getPrice());
                    $product->setSupplier($supplier);

                    $this->domainEntityManager->persist($product);
                } else {
                    $product->setName($vo->getName());
                    $product->setPrice($vo->getPrice());
                    $product->setSupplier($supplier);
                }
            }
        }
        $this->domainEntityManager->flush(); // Executes all updates.
    }
}
