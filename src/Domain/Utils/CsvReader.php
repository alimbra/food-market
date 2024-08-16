<?php

namespace App\Domain\Utils;

use App\Domain\Dto\FileDto;
use Exception;

class CsvReader
{
    private const FALLBACK_SEPARATOR = "\t";

    /**
     * @return resource
     *
     * @throws Exception
     * @codeCoverageIgnore
     */
    public static function openFile(FileDto $file)
    {
        $handle = fopen($file->getFilePath(), 'rb');
        if (!$handle) {
            throw new Exception('Unable to read file ' . $file->getFilePath());
        }

        return $handle;
    }

    /**
     * @param resource $handle
     */
    public static function closeFile($handle): void
    {
        fclose($handle);
    }

    /**
     * @param resource $handle
     *
     * @return ?array<string>
     */
    public static function readNextLine($handle): ?array
    {
        while ($line = fgetcsv($handle, 0)) {
            if (count($line) > 1) {
                return $line;
            }

            // If main delimiter is not found, try to fallback on another that we accept
            $line = explode(self::FALLBACK_SEPARATOR, (string) current($line));
            if (count($line) > 1) {
                return $line;
            }
        }

        return null;
    }
}
