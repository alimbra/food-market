<?php

namespace App\Domain\Data;

interface RegistrySupplierInterface
{
    public function findOneBy(array $criteria, array $orderBy = null): ?object;
}