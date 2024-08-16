<?php

namespace App\Domain\FileHandling;

use App\Domain\Dto\FileDto;
use App\Domain\Entity\ImportFile;
use App\Domain\Utils\CsvReader;
use Exception;

readonly class FromImportFile
{
    public function __construct(protected ImportFile $importFile)
    {
    }

    public function getDto(string $directory): FileDto
    {
        $name = explode('.', $this->importFile->getOriginalFilename());
        $extension = end($name);

        return new FileDto(
            $this->importFile->getFilename(),
            $directory . $this->importFile->getOriginalFilename(),
            $extension
        );
    }

    /**
     * @throws Exception
     */
    public function getContentOf(string $directory): array
    {
        $handle = CSVReader::openFile($this->getDto($directory));

        $results = [];
        while ($line = CSVReader::readNextLine($handle)) {
            $results[] = implode($line);
        }

        return  $results;
    }
}
