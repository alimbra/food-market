<?php

declare(strict_types=1);

namespace App\Domain\Dto;

use App\Domain\Data\RegistryProductInterface;
use Countable;
use IteratorAggregate;
use Traversable;

class ProductListCollectionDto implements IteratorAggregate, Countable
{
    private iterable $productList = [];
    private int $count = 0;
    public function getIterator(): Traversable
    {
        yield from $this->productList;
    }

    public function count(): int
    {
        return $this->count;
    }
    public function setProduct(iterable $product): void
    {

        $this->productList = $product;
    }

    public function getMax(): float
    {
        return ceil($this->count() / RegistryProductInterface::LIMIT);
    }

    public function setCount(int $count): void
    {
        $this->count = $count;
    }
}
