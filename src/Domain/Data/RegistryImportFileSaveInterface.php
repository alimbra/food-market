<?php

declare(strict_types=1);

namespace App\Domain\Data;

interface RegistryImportFileSaveInterface
{
    public function findAll(): array;
    public function findOneBy(array $criteria, array $orderBy = null): ?object;
}