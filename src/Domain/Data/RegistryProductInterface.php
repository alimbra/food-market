<?php

declare(strict_types=1);

namespace App\Domain\Data;

use App\Domain\Dto\ProductDataFilterDTOInterface;
use App\Domain\Dto\ProductListCollectionDto;

interface RegistryProductInterface
{
    public const LIMIT = 12;
    public function findOneBy(array $criteria, array $orderBy = null): ?object;

    public function paginated(int $page, ?ProductDataFilterDTOInterface $filterDTO = null): ProductListCollectionDto;
}
