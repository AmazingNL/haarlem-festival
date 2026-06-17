<?php

declare(strict_types=1);

namespace App\Services;

final class ReservationEmailService
{
    private IMailer $mailer;

    public function __construct(IMailer $mailer)
    {
        $this->mailer = $mailer;
    }

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

        $this->mailer->send(
            $email,
            $name,
            'Your Ratatouille reservation was added to My Program',
            $this->buildHtmlBody($name, $reservation),
            $this->buildTextBody($name, $reservation)
        );
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
