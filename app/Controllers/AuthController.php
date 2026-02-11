<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\ControllerBase;

final class AuthController extends ControllerBase
{
    public function login(): void
    {
        $this->render('auth/login', [
            'csrf_token' => $this->csrfToken()
        ]);
    }

    public function processLogin(): void
    {
        $this->verifyCsrf();

        $identifier = $this->str('login_id');
        $password   = $this->str('password');

        if ($this->userService === null) {
            $this->abort(500, 'User service not available');
        }

        $user = $this->userService->authenticate($identifier, $password);

        if ($user !== null) {
            $_SESSION['user_id'] = $user->user_id;
            $_SESSION['role']    = $user->role->value;

            $this->handleRedirect($user);
            return;
        }

        $this->render('auth/login', [
            'error'      => 'Invalid email/username or password',
            'csrf_token' => $this->csrfToken()
        ]);
    }

    public function logout(): void
    {
        session_destroy();
        $this->redirect('/login');
    }

    public function register(): void
    {
        $this->render('auth/register', [
            'csrf_token' => $this->csrfToken()
        ]);
    }
}
