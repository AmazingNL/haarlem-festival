<?php

declare(strict_types=1);

namespace App\Services;

interface IMailer
{
    /**
     * @throws \InvalidArgumentException when the recipient address is missing or invalid.
     */
    public function send(
        string $toEmail,
        string $toName,
        string $subject,
        string $htmlBody,
        string $textBody = '',
        array $attachments = []
    ): void;
}
