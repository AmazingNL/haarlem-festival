<?php

declare(strict_types=1);

namespace App\Services\Implementations;

use App\Services\Interfaces\IMailer;

use PHPMailer\PHPMailer\PHPMailer;

final class Mailer implements IMailer
{
    public function send(string $toEmail, string $toName, string $subject, string $htmlBody, string $textBody = '', array $attachments = []): void
    {
        $toEmail = trim($toEmail);
        if ($toEmail === '' || !filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        if (trim($toName) === '') {
            $toName = 'Festival guest';
        }

        if (trim($textBody) === '') {
            $textBody = trim(strip_tags($htmlBody));
        }

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = trim((string) ($_ENV['SMTP_HOST'] ?? 'mailpit'));
        $mail->Port = (int) ($_ENV['SMTP_PORT'] ?? 1025);
        $mail->SMTPAuth = trim((string) ($_ENV['SMTP_USER'] ?? '')) !== '';

        if ($mail->SMTPAuth) {
            $mail->Username = (string) $_ENV['SMTP_USER'];
            $mail->Password = (string) ($_ENV['SMTP_PASS'] ?? '');
        }

        $mail->setFrom($this->fromAddress(), $this->fromName());
        $mail->addAddress($toEmail, $toName);
        $mail->Subject = $subject;
        $mail->isHTML(true);
        $mail->Body = $htmlBody;
        $mail->AltBody = $textBody;

        foreach ($attachments as $attachment) {
            $content = (string) ($attachment['content'] ?? '');
            $filename = trim((string) ($attachment['filename'] ?? ''));
            if ($content === '' || $filename === '') {
                continue;
            }
            $mail->addStringAttachment(
                $content,
                $filename,
                PHPMailer::ENCODING_BASE64,
                (string) ($attachment['mime'] ?? 'application/octet-stream')
            );
        }

        $mail->send();
    }

    private function fromAddress(): string
    {
        return trim((string) ($_ENV['MAIL_FROM_ADDRESS'] ?? 'no-reply@haarlem-festival.local'));
    }

    private function fromName(): string
    {
        return trim((string) ($_ENV['MAIL_FROM_NAME'] ?? 'Haarlem Festival'));
    }
}
