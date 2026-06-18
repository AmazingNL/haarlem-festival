<?php

declare(strict_types=1);

namespace App\Services\Implementations;

use App\Services\Interfaces\IAccountEmailService;
use App\Services\Interfaces\IMailer;

use App\Models\User;

/**
 * Builds and sends account-related emails (currently the registration welcome).
 * Delegates the actual transport to an IMailer so it stays testable and decoupled.
 */
final class AccountEmailService implements IAccountEmailService
{
    private IMailer $mailer;

    public function __construct(IMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendWelcome(User $user): void
    {
        $name = $this->displayName($user);

        $this->mailer->send(
            $user->email,
            $name,
            'Welcome to the Haarlem Festival',
            $this->buildHtmlBody($name),
            $this->buildTextBody($name)
        );
    }

    private function displayName(User $user): string
    {
        $name = trim($user->first_name . ' ' . $user->last_name);
        if ($name !== '') {
            return $name;
        }

        return $user->username !== '' ? $user->username : 'Festival guest';
    }

    private function buildHtmlBody(string $name): string
    {
        $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        $loginUrl = $this->loginUrl();
        $loginLink = $loginUrl !== ''
            ? '<p><a href="' . htmlspecialchars($loginUrl, ENT_QUOTES, 'UTF-8') . '">Log in to your account</a></p>'
            : '';

        return '<p>Hello ' . $safeName . ',</p>'
            . '<p>Welcome to the Haarlem Festival! Your account has been created successfully.</p>'
            . '<p>You can now log in to browse events, build your personal program and purchase tickets.</p>'
            . $loginLink
            . '<p>See you at the festival!</p>';
    }

    private function buildTextBody(string $name): string
    {
        $loginUrl = $this->loginUrl();
        $loginLine = $loginUrl !== '' ? "\n\nLog in: {$loginUrl}" : '';

        return "Hello {$name},\n\n"
            . "Welcome to the Haarlem Festival! Your account has been created successfully.\n\n"
            . "You can now log in to browse events, build your personal program and purchase tickets."
            . $loginLine
            . "\n\nSee you at the festival!";
    }

    private function loginUrl(): string
    {
        $base = rtrim(trim((string) ($_ENV['APP_URL'] ?? '')), '/');
        return $base === '' ? '' : $base . '/loginForm';
    }
}
