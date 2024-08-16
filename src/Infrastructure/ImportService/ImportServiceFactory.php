<?php

declare(strict_types=1);

namespace App\Infrastructure\ImportService;

use App\Domain\ImportService\ImportFileCsvService;
use App\Domain\ImportService\ImportFileInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

class ImportServiceFactory
{
    public const CSV = 'csv';
    /**
     * @see service.yaml
     */
    public function __construct(readonly private ContainerInterface $locator)
    {
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getImportFile(string $extension): ImportFileInterface
    {
        $factory = match ($extension) {
            self::CSV => $this->locator->get(ImportFileCsvService::class),
            default => throw new ServiceNotFoundException($extension),
        };

        if (!$factory instanceof ImportFileInterface) {
            throw new RuntimeException('expect instance of ImportFileInterface');
        }

        return $factory;
    }
}
