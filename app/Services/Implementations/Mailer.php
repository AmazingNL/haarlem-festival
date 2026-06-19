<?php

declare(strict_types=1);

namespace App\Services\Implementations;

use App\Services\Interfaces\IMailer;
use PHPMailer\PHPMailer\Exception as MailerException;
use PHPMailer\PHPMailer\PHPMailer;

final class Mailer implements IMailer
{
    public function send(
        string $toEmail,
        string $toName,
        string $subject,
        string $htmlBody,
        string $textBody = '',
        array $attachments = []
    ): void {
        $toEmail = trim($toEmail);
        if ($toEmail === '' || !filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Cannot send email: recipient address is missing or invalid.');
        }

        if (trim($toName) === '') {
            $toName = 'Festival guest';
        }

        if (trim($textBody) === '') {
            $textBody = trim(strip_tags($htmlBody));
        }

        $mail = new PHPMailer(true);

        try {
            $this->configureSmtp($mail);

            $mail->setFrom($this->fromAddress(), $this->fromName());
            $mail->addAddress($toEmail, $toName);
            $mail->Subject = $subject;
            $mail->isHTML(true);
            $mail->Body = $htmlBody;
            $mail->AltBody = $textBody;

            foreach ($attachments as $attachment) {
                if (!is_array($attachment)) {
                    continue;
                }

                $content = $attachment['content'] ?? '';
                $filename = trim((string) ($attachment['filename'] ?? ''));
                if ($content === '' || $filename === '') {
                    continue;
                }

                $mail->addStringAttachment(
                    $content,
                    $filename,
                    'base64',
                    (string) ($attachment['mime'] ?? 'application/octet-stream')
                );
            }

            $mail->send();
        } catch (MailerException $e) {
            error_log('SMTP send failed to ' . $toEmail . ': ' . $mail->ErrorInfo);
            throw new \RuntimeException('Email could not be sent: ' . $mail->ErrorInfo, 0, $e);
        }
    }

    private function configureSmtp(PHPMailer $mail): void
    {
        $host = trim((string) ($_ENV['SMTP_HOST'] ?? 'mailpit'));
        $port = (int) ($_ENV['SMTP_PORT'] ?? 1025);
        $user = trim((string) ($_ENV['SMTP_USER'] ?? ''));
        $pass = str_replace(' ', '', trim((string) ($_ENV['SMTP_PASS'] ?? '')));
        $encryption = strtolower(trim((string) ($_ENV['SMTP_ENCRYPTION'] ?? '')));

        $mail->isSMTP();
        $mail->Host = $host;
        $mail->Port = $port;
        $mail->CharSet = PHPMailer::CHARSET_UTF8;
        $mail->Timeout = 30;
        $mail->SMTPAuth = $user !== '';

        if ($mail->SMTPAuth) {
            $mail->Username = $user;
            $mail->Password = $pass;
        }

        if ($encryption === 'tls' || $encryption === 'starttls' || $port === 587) {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        } elseif ($encryption === 'ssl' || $encryption === 'smtps' || $port === 465) {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        }
    }

    private function fromAddress(): string
    {
        $from = trim((string) ($_ENV['MAIL_FROM_ADDRESS'] ?? ''));
        if ($from !== '' && filter_var($from, FILTER_VALIDATE_EMAIL)) {
            return $from;
        }

        $smtpUser = trim((string) ($_ENV['SMTP_USER'] ?? ''));
        if ($smtpUser !== '' && filter_var($smtpUser, FILTER_VALIDATE_EMAIL)) {
            return $smtpUser;
        }

        return 'no-reply@haarlem-festival.local';
    }

    private function fromName(): string
    {
        return trim((string) ($_ENV['MAIL_FROM_NAME'] ?? 'Haarlem Festival'));
    }
}
