<?php

declare(strict_types=1);

namespace App\Domain\Query;

use Traversable;

interface AllProductsWithSupplierQueryInterface
{

    public function findAllProductsWithSupplier(): Traversable;
}
