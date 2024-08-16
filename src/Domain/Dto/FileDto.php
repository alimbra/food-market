<?php

declare(strict_types=1);

namespace App\Domain\Dto;

readonly class FileDto
{
    public function __construct(
        protected string $originalFileName,
        protected string $filePath,
        protected string $extension,
    ){}

    public function getFilePath(): string
    {
        return $this->filePath;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * @return string
     */
    public function getOriginalFileName(): string
    {
        return $this->originalFileName;
    }
}