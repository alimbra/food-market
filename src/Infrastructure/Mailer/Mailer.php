<?php

declare(strict_types=1);

namespace App\Infrastructure\Mailer;

use App\Domain\Mailer\CustomMailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class Mailer implements CustomMailerInterface
{
    public function __construct(
        protected MailerInterface $mailer,
        protected string $from,
        protected string $to,
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendMail(): void
    {

        $email = (new Email())
            ->from($this->from)
            ->to($this->to)
            ->subject("Confirmation import")
            ->text("Finishing Import")
            ->html("<p>Finishing Import</p>");
        ;
        $this->mailer->send($email);
    }
}
