<?php

namespace App\Tests\Unitary;

use App\Domain\Dto\ProductDataFilterDTOInterface;

class DummyProductDataFilterDTO implements ProductDataFilterDTOInterface
{

    public function getCode(): ?string
    {
        return '456245';
    }

    public function getName(): string
    {
        return '';
    }
}