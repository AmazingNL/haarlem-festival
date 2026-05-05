<?php

declare(strict_types=1);

namespace App\Services;

final class ReservationEmailService
{
    public function sendReservationAdded(array $customer, array $reservation): void
    {
        $email = trim((string) ($customer['email'] ?? ''));
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        $name = trim((string) ($customer['first_name'] ?? '') . ' ' . (string) ($customer['last_name'] ?? ''));
        if ($name === '') {
            $name = 'Festival guest';
        }

        if (class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
            $this->sendWithPhpMailer($email, $name, $reservation);
            return;
        }

        $this->sendWithNativeSmtp($email, $name, $reservation);
    }

    private function sendWithPhpMailer(string $email, string $name, array $reservation): void
    {
        $mailerClass = 'PHPMailer\\PHPMailer\\PHPMailer';
        $mail = new $mailerClass(true);
        $mail->isSMTP();
        $mail->Host = trim((string) ($_ENV['SMTP_HOST'] ?? 'mailpit'));
        $mail->Port = (int) ($_ENV['SMTP_PORT'] ?? 1025);
        $mail->SMTPAuth = trim((string) ($_ENV['SMTP_USER'] ?? '')) !== '';

        if ($mail->SMTPAuth) {
            $mail->Username = (string) $_ENV['SMTP_USER'];
            $mail->Password = (string) ($_ENV['SMTP_PASS'] ?? '');
        }

        $fromAddress = trim((string) ($_ENV['MAIL_FROM_ADDRESS'] ?? 'no-reply@haarlem-festival.local'));
        $fromName = trim((string) ($_ENV['MAIL_FROM_NAME'] ?? 'Haarlem Festival'));

        $mail->setFrom($fromAddress, $fromName);
        $mail->addAddress($email, $name);
        $mail->Subject = 'Your Ratatouille reservation was added to My Program';
        $mail->isHTML(true);
        $mail->Body = $this->buildHtmlBody($name, $reservation);
        $mail->AltBody = $this->buildTextBody($name, $reservation);
        $mail->send();
    }

    private function sendWithNativeSmtp(string $email, string $name, array $reservation): void
    {
        $host = trim((string) ($_ENV['SMTP_HOST'] ?? 'mailpit'));
        $port = (int) ($_ENV['SMTP_PORT'] ?? 1025);
        $fromAddress = trim((string) ($_ENV['MAIL_FROM_ADDRESS'] ?? 'no-reply@haarlem-festival.local'));
        $fromName = trim((string) ($_ENV['MAIL_FROM_NAME'] ?? 'Haarlem Festival'));
        $body = $this->buildTextBody($name, $reservation);

        $socket = @fsockopen($host, $port, $errorCode, $errorMessage, 5);
        if (!is_resource($socket)) {
            throw new \RuntimeException('Could not connect to SMTP server: ' . $errorMessage, $errorCode);
        }

        stream_set_timeout($socket, 5);
        $this->readSmtp($socket);
        $this->writeSmtp($socket, 'HELO haarlem-festival.local');
        $this->writeSmtp($socket, 'MAIL FROM:<' . $fromAddress . '>');
        $this->writeSmtp($socket, 'RCPT TO:<' . $email . '>');
        $this->writeSmtp($socket, 'DATA');

        $message = implode("\r\n", [
            'From: ' . $this->formatAddress($fromAddress, $fromName),
            'To: ' . $this->formatAddress($email, $name),
            'Subject: Your Ratatouille reservation was added to My Program',
            'MIME-Version: 1.0',
            'Content-Type: text/plain; charset=UTF-8',
            '',
            $body,
            '.',
        ]);

        $this->writeRawSmtp($socket, $message . "\r\n");
        $this->readSmtp($socket);
        $this->writeSmtp($socket, 'QUIT');
        fclose($socket);
    }

    private function formatAddress(string $email, string $name): string
    {
        $cleanName = trim(str_replace(['"', "\r", "\n"], '', $name));
        if ($cleanName === '') {
            return '<' . $email . '>';
        }

        return '"' . $cleanName . '" <' . $email . '>';
    }

    private function writeSmtp(mixed $socket, string $command): void
    {
        $this->writeRawSmtp($socket, $command . "\r\n");
        $this->readSmtp($socket);
    }

    private function writeRawSmtp(mixed $socket, string $data): void
    {
        fwrite($socket, $data);
    }

    private function readSmtp(mixed $socket): string
    {
        $response = '';
        while (($line = fgets($socket, 515)) !== false) {
            $response .= $line;
            if (strlen($line) >= 4 && $line[3] === ' ') {
                break;
            }
        }

        $code = (int) substr($response, 0, 3);
        if ($code >= 400) {
            throw new \RuntimeException('SMTP error: ' . trim($response));
        }

        return $response;
    }

    private function buildHtmlBody(string $name, array $reservation): string
    {
        $lines = $this->reservationLines($reservation);
        $items = implode('', array_map(
            static fn(string $line): string => '<li>' . htmlspecialchars($line, ENT_QUOTES, 'UTF-8') . '</li>',
            $lines
        ));

        return '<p>Hello ' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . ',</p>'
            . '<p>Your Ratatouille reservation has been added to My Program.</p>'
            . '<ul>' . $items . '</ul>'
            . '<p>You can finish checkout from My Program.</p>';
    }

    private function buildTextBody(string $name, array $reservation): string
    {
        return "Hello {$name},\n\n"
            . "Your Ratatouille reservation has been added to My Program.\n\n"
            . implode("\n", array_map(static fn(string $line): string => '- ' . $line, $this->reservationLines($reservation)))
            . "\n\nYou can finish checkout from My Program.";
    }

    private function reservationLines(array $reservation): array
    {
        $total = number_format((float) ($reservation['total_price'] ?? 0), 2, '.', '');

        return array_values(array_filter([
            'Date: ' . trim((string) ($reservation['day'] ?? '')),
            'Session: ' . trim((string) ($reservation['time'] ?? '')),
            'Guests: ' . trim((string) ($reservation['ticket_summary_text'] ?? '')),
            'Total reservation fee: EUR ' . $total,
            trim((string) ($reservation['special_requests'] ?? '')) !== ''
                ? 'Special requests: ' . trim((string) $reservation['special_requests'])
                : '',
        ]));
    }
}