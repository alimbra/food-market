<?php

namespace App\Domain\Validator;

use App\Domain\Dto\ValueObjectProduct;

interface ValidatorProductInterface
{
    public function validate(ValueObjectProduct $product): void;
}
