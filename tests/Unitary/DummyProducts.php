<?php

namespace App\Tests\Unitary;

use App\Domain\Entity\Product;
use App\Domain\Entity\Supplier;

class DummyProducts
{
    public function __construct(protected Supplier $supplier, protected array $products = [])
    {
        for ($i = 0; $i < 50; $i++) {
            $product = new Product();
            $price = $i + 0.3;
            $product->setName('product -1' . $i);
            $product->setCode('45624' . $i);
            $product->setPrice($price);
            $supplier->addProduct($product);
            $this->products[] = $product;
        }
    }

    /**
     * @return array<Product>
     */
    public function getProducts(): array
    {
        return $this->products;
    }
}