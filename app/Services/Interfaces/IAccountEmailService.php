<?php

declare(strict_types=1);

namespace App\Services\Interfaces;

use App\Models\User;

interface IAccountEmailService
{
    /**
     * Send the welcome/confirmation email after a new account is created.
     */
    public function sendWelcome(User $user): void;
}
