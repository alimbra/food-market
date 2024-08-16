<?php

declare(strict_types=1);

namespace App\Domain\StatusFileUpdated;

use App\Domain\Entity\ImportFile;

interface UpdateFileStateInterface
{
    public function successImport(ImportFile $importFile): void;
    public function failureImport(ImportFile $importFile): void;
}
