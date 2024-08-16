<?php

declare(strict_types=1);

namespace App\Domain\ImportService;

use App\Domain\Dto\FileDto;

interface ImportFileInterface
{
    public function treatmentFile(FileDto $fileDto, int $supplierId): void;
}
