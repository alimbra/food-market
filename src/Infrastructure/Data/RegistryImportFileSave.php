<?php

namespace App\Infrastructure\Data;

use App\Domain\Data\RegistryImportFileSaveInterface;
use App\Domain\Entity\ImportFile;
use Doctrine\ORM\EntityManagerInterface;

class RegistryImportFileSave implements RegistryImportFileSaveInterface
{

    public function __construct(protected EntityManagerInterface $entityManager)
    {
    }

    public function findAll(): array
    {
        return $this->entityManager->getRepository(ImportFile::class)->findAll();
    }

    public function findOneBy(array $criteria, array $orderBy = null): ?object
    {
        return $this->entityManager->getRepository(ImportFile::class)->findOneBy($criteria, $orderBy);
    }
}