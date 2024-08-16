<?php

declare(strict_types=1);

namespace App\Domain\Mailer;

interface CustomMailerInterface
{
    public function sendMail(): void;
}
