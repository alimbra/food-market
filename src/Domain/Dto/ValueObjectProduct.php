<?php

namespace App\Domain\Dto;

use InvalidArgumentException;

readonly class ValueObjectProduct
{
    private function __construct(
        protected string $name,
        protected string $code,
        protected float $price
    ) {
    }

    public static function fromArray(array $data): ?ValueObjectProduct
    {
        if (3 !== count($data)) {
            return null;
        }
        self::validate($data);

        return new ValueObjectProduct($data[0], $data[1], $data[2]);
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param array $data
     */
    protected static function validate(array $data): void
    {
        if (!is_numeric($data[2])) {
            throw new InvalidArgumentException('Price must be a float');
        }

        if ($data[2] < 0) {
            throw new InvalidArgumentException($data[2]);
        }
        if ($data[2] > 1000) {
            throw new InvalidArgumentException($data[2] . "is abnormal price");
        }
        if (strlen($data[1]) > 6) {
            throw new InvalidArgumentException($data[1] . "is not a valid product code");
        }
    }
}
