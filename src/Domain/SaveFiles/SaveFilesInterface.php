<?php

declare(strict_types=1);

namespace App\Domain\SaveFiles;

use App\Domain\Dto\FileDto;
use App\Domain\Entity\Supplier;

interface SaveFilesInterface
{
    public function saveFile(FileDto $fileDto, Supplier $supplier): void;
}
