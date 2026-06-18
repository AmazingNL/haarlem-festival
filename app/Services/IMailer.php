<?php

declare(strict_types=1);

namespace App\Services;

interface IMailer
{
    /**
     * Send a single email. Silently ignores empty/invalid recipient addresses.
     *
     * @param string $toEmail  Recipient email address.
     * @param string $toName   Recipient display name.
     * @param string $subject  Email subject line.
     * @param string $htmlBody HTML body.
     * @param string $textBody Plain-text alternative; derived from the HTML body when omitted.
     * @param list<array{content: string, filename: string, mime?: string}> $attachments Binary attachments to include.
     */
    public function send(string $toEmail, string $toName, string $subject, string $htmlBody, string $textBody = '', array $attachments = []): void;
}
