<?php

namespace App\Domain\ImportService;

use App\Domain\Dto\FileDto;
use App\Domain\Messenger\MessengerInterface;
use App\Infrastructure\Message\FileMessage;
use Psr\Log\LoggerInterface;
use Throwable;

class ImportFileCsvService implements ImportFileInterface
{
    public function __construct(
        protected LoggerInterface $logger,
        protected MessengerInterface $messageBus,
    ) {
    }

    public function treatmentFile(FileDto $fileDto, int $supplierId): void
    {
        try {
            $this->messageBus->dispatch(new FileMessage($fileDto, $supplierId));
        } catch (Throwable $e) {
            $this->logger->error($e, [Throwable::class => $e::class]);
        }
    }
}
