<?php

declare(strict_types=1);

namespace App\Domain\ImportService;

use App\Domain\Dto\FileDto;
use App\Domain\Entity\Supplier;
use App\Infrastructure\ImportService\ImportServiceFactory;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ImportService
{
    public function __construct(protected ImportServiceFactory $factory)
    {
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function import(FileDto $fileDto, Supplier $supplier): void
    {
        $service = $this->factory->getImportFile($fileDto->getExtension());

        $service->treatmentFile($fileDto, $supplier->getId());
    }
}