<?php

namespace App\Infrastructure\MessageBus;
use App\Domain\Messenger\MessengerInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class MessageBus implements MessengerInterface
{

    public function __construct(protected MessageBusInterface $messageBus)
    {
    }

    /**
     * @throws ExceptionInterface
     */
    public function dispatch(object $message, array $stamps = []): object
    {
        return $this->messageBus->dispatch($message, $stamps);
    }
}