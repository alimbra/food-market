<?php

namespace App\Infrastructure\Data;

use App\Domain\Data\RegistrySupplierInterface;
use App\Domain\Entity\Supplier;
use Doctrine\ORM\EntityManagerInterface;

class ManagerSupplierRegistry implements RegistrySupplierInterface
{
    public function __construct(protected EntityManagerInterface $entityManager)
    {
    }

    public function findOneBy(array $criteria, array $orderBy = null): ?object
    {
        return $this->entityManager->getRepository(Supplier::class)->findOneBy($criteria, $orderBy);
    }
}