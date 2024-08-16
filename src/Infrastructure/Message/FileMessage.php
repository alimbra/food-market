<?php

namespace App\Infrastructure\Message;

use App\Domain\Dto\FileDto;

final class FileMessage
{
    /*
     * Add whatever properties and methods you need
     * to hold the data for this message class.
     */

    public function __construct(
        protected FileDto $fileDto,
        protected int $supplierId
    ) {
    }

    public function getFileDto(): FileDto
    {
        return $this->fileDto;
    }

    public function getSupplierId(): int
    {
        return $this->supplierId;
    }
}
