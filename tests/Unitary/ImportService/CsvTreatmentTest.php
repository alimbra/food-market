<?php

declare(strict_types=1);

namespace App\Tests\Unitary\ImportService;

use App\Application\CsvTreatment\CsvTreatment;
use App\Domain\Dto\FileDto;
use App\Domain\Entity\ImportFile;
use App\Domain\Entity\Product;
use App\Domain\Entity\Supplier;
use App\Domain\Enum\EnumImportFileStatus;
use App\Infrastructure\Message\FileMessage;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CsvTreatmentTest extends KernelTestCase
{
    /**
     * @throws Exception
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::bootKernel();

        $em = self::getContainer()->get(EntityManagerInterface::class);
        self::purgeAll($em);

        $supplier = new Supplier();
        $supplier->setName('supplier');
        $em->persist($supplier);

        $import = new ImportFile();
        $import->setSupplier($supplier);
        $import->setFilename('file.csv');
        $import->setOriginalFilename('file.csv');
        $import->setDateImport(new DateTimeImmutable());
        $import1 = new ImportFile();
        $import1->setSupplier($supplier);
        $import1->setFilename('file1.csv');
        $import1->setOriginalFilename('file1.csv');
        $import1->setDateImport(new DateTimeImmutable());
        $em->persist($import);
        $em->persist($import1);
        $em->flush();
    }

    /**
     * @throws Exception
     */
    public function testTreatmentFileFirstTameSuccesfull(): void
    {
        $service = $this->getContainer()->get(CsvTreatment::class);
        self::assertInstanceOf(CsvTreatment::class, $service);

        $fileDto = new FileDto('file.csv', __DIR__ . '/file.csv', 'csv');
        $fileMessage = new FileMessage($fileDto, 1);

        $service->treatmentFile($fileMessage);

        /** @var EntityManagerInterface $em */
        $em = self::getContainer()->get(EntityManagerInterface::class);

        $products = $em->getRepository(Product::class)->findAll();
        self::assertCount(3, $products);
        self::assertSame(17.71, $products[0]->getPrice());
        self::assertSame(
            EnumImportFileStatus::DONE->value,
            $em->getRepository(ImportFile::class)->find(1)->getStatus()
        );
    }

    /**
     * @throws Exception
     */
    public function testTreatmentSecondFileSuccesfull(): void
    {
        $service = $this->getContainer()->get(CsvTreatment::class);
        self::assertInstanceOf(CsvTreatment::class, $service);

        $fileDto = new FileDto('file1.csv', __DIR__ . '/file1.csv', 'csv');
        $fileMessage = new FileMessage($fileDto, 1);

        $service->treatmentFile($fileMessage);

        /** @var EntityManagerInterface $em */
        $em = self::getContainer()->get(EntityManagerInterface::class);

        $products = $em->getRepository(Product::class)->findAll();
        self::assertCount(3, $products);
        self::assertSame(27.71, $products[0]->getPrice());
        self::assertSame(
            EnumImportFileStatus::DONE->value,
            $em->getRepository(ImportFile::class)->find(2)->getStatus()
        );
    }

    /**
     * @throws Exception
     */
    public static function purgeAll(EntityManagerInterface $em): void
    {
        $em->getConnection()
            ->executeQuery('SET FOREIGN_KEY_CHECKS = 0');
        foreach (['import_file', 'product', 'supplier'] as $tableName) {
            $em->getConnection()
                ->executeQuery("TRUNCATE TABLE `$tableName`");
        }
        $em->getConnection()
            ->executeQuery('SET FOREIGN_KEY_CHECKS = 1');
    }
}
