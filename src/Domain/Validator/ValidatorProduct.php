<?php

declare(strict_types=1);

namespace App\Domain\Validator;

use App\Domain\Dto\ValueObjectProduct;
use InvalidArgumentException;

class ValidatorProduct implements ValidatorProductInterface
{
    public function validate(ValueObjectProduct $product): void
    {
        if ($product->getPrice() < 0) {
            throw new InvalidArgumentException($product->getPrice() . ' is not a positive number');
        }

        if ($product->getPrice() > 1000) {
            throw new InvalidArgumentException($product->getPrice() . "is abnormal price");
        }

        if (strlen($product->getCode()) > 6) {
            throw new InvalidArgumentException($product->getCode() . "is not a valid product code");
        }
    }
}
