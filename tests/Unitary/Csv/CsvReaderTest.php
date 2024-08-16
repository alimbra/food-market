<?php

namespace App\Tests\Unitary\Csv;

use App\Domain\Dto\FileDto;
use App\Domain\Utils\CsvReader;
use Exception;
use PHPUnit\Framework\TestCase;

class CsvReaderTest extends TestCase
{
    /** @var resource|null */
    private $handle;

    protected function tearDown(): void
    {
        if ($this->handle) {
            CSVReader::closeFile($this->handle);
            $this->handle = null;
        }
    }

    /**
     * @throws Exception
     */
    public function testReadNextLineAssertIgnoreEmptyLine(): void
    {
        $this->initFile('emptyFile.csv');
        $lines = [];
        while (($line = CSVReader::readNextLine($this->handle))) {
            $lines[] = $line;
        }
        self::assertCount(0, $lines);
    }

    /**
     * @throws Exception
     */
    public function testReadNextLineCorrectlyHandleFallbackSeparator(): void
    {
        $this->initFile('file.csv');
        $lines = [];
        while (($line = CSVReader::readNextLine($this->handle))) {
            $lines[] = $line;
        }
        self::assertCount(2, $lines);
        self::assertSame(['i','am','the','last','line'], $lines[1]);
    }

    /**
     * @throws Exception
     */
    private function initFile(string $fileName): void
    {
        $fileDto = new FileDto($fileName, __DIR__ . '/' . $fileName, 'csv');
        $this->handle = CSVReader::openFile($fileDto);
        self::assertIsResource($this->handle);
    }
}
