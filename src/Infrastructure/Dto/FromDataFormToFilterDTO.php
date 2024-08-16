<?php

namespace App\Infrastructure\Dto;

use App\Domain\Dto\ProductDataFilterDTOInterface;
use Symfony\Component\HttpFoundation\Request;

readonly class FromDataFormToFilterDTO implements ProductDataFilterDTOInterface
{
    public function __construct(
        protected Request $request
    ) {
    }

    public function getCode(): ?string
    {
        return $this->request->get('search_product_form')['code'] ?? null;
    }

    public function getName(): string
    {
        return $this->request->get('search_product_form')['name'] ?? '';
    }
}
