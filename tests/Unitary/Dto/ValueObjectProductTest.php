<?php

declare(strict_types=1);

namespace App\Tests\Unitary\Dto;

use App\Domain\Dto\ValueObjectProduct;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ValueObjectProductTest extends TestCase
{
    public function testFromArrayEmpty(): void
    {
        $valueObject = ValueObjectProduct::fromArray([]);
        self::assertNull($valueObject);
    }

    public function testFromArrayWithManyData(): void
    {
        $valueObject = ValueObjectProduct::fromArray(['nom', 'noqsmd', 'omsfsdf', 'sqdfmsdmf', 'sdfksd']);
        self::assertNull($valueObject);
    }

    public function testFromArrayWithWrongData(): void
    {
        self::expectException(InvalidArgumentException::class);
        ValueObjectProduct::fromArray(['nom', 'noqsmd', 'omsfsdf']);
    }

    public function testFromArrayWithNegativePrice(): void
    {
        self::expectException(InvalidArgumentException::class);
        ValueObjectProduct::fromArray(['nom', 'noqsmd', -15000]);
    }

    public function testFromArrayWithHightPrice(): void
    {
        self::expectException(InvalidArgumentException::class);
        ValueObjectProduct::fromArray(['nom', 'noqsmd', 10505]);
    }

    public function testFromArrayWithWrongCode(): void
    {
        self::expectException(InvalidArgumentException::class);
        ValueObjectProduct::fromArray(['nom', '5d88f7smd', 15]);
    }

    public function testFromArrayWithCorrectData(): void
    {
        self::assertInstanceOf(
            ValueObjectProduct::class,
            ValueObjectProduct::fromArray(['nom', '5d5smd', 15])
        );
    }
}
