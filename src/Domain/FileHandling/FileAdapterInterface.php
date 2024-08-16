<?php

declare(strict_types=1);

namespace App\Domain\FileHandling;

use App\Domain\Dto\FileDto;

interface FileAdapterInterface
{
    public function getFileDTO(): FileDTO;
}
