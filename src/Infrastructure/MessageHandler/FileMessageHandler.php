<?php

namespace App\Infrastructure\MessageHandler;

use App\Application\CsvTreatment\CsvTreatment;
use App\Infrastructure\Message\FileMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class FileMessageHandler
{
    public function __construct(protected CsvTreatment $csvTreatment, protected LoggerInterface $logger)
    {
    }

    public function __invoke(FileMessage $message): void
    {
        try {
            $this->csvTreatment->treatmentFile($message);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
