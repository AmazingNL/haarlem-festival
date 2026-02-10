<?php

declare(strict_types=1);
namespace App\Controllers;
use App\Core\Controller;
use App\Core\ControllerBase;
use App\Models\User;
final class AuthController extends ControllerBase
{
    public function login(): void
    {
        $this->render('auth/login');
    }

    public function register(): void
    {
        $this->render('auth/register');
    }
}