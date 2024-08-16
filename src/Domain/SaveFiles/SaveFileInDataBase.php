<?php

declare(strict_types=1);

namespace App\Domain\SaveFiles;

use App\Domain\Data\DomainEntityManagerInterface;
use App\Domain\Dto\FileDto;
use App\Domain\Entity\ImportFile;
use App\Domain\Entity\Supplier;
use DateTimeImmutable;

class SaveFileInDataBase implements SaveFilesInterface
{
    public function __construct(protected DomainEntityManagerInterface $entityManager)
    {
    }

    public function saveFile(FileDto $fileDto, Supplier $supplier): void
    {
        $importFile = new ImportFile();
        $importFile->setDateImport(new DateTimeImmutable());
        $importFile->setOriginalFilename($fileDto->getFilePath());
        $importFile->setFilename($fileDto->getOriginalFileName());
        $importFile->setSupplier($supplier);

        $this->entityManager->persist($importFile);
        $this->entityManager->flush();
    }
}
