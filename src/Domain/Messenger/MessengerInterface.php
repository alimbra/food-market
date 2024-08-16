<?php

declare(strict_types=1);

namespace App\Domain\Messenger;

interface MessengerInterface
{
    public function dispatch(object $message, array $stamps = []): object;
}
