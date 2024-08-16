<?php

declare(strict_types=1);

namespace App\Domain\Dto;

interface ProductDataFilterDTOInterface
{
    public function getCode(): ?string;

    public function getName(): string;
}
