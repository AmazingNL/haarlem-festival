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
        $this->render('auth/login', ['csrf_token' => $this->csrfToken()]);
    }

    // David

   public function processLogin(): void
    {
        $this->verifyCsrf();

        $identifier = $this->str('login_id'); 
        $password = $this->str('password');

        $user = $this->userRepository->findUserByEmail($identifier);

        if ($user && password_verify($password, $user->password_hash)) {
            
            $_SESSION['user_id'] = $user->user_id;
            $_SESSION['role'] = $user->role->value;

            $this->handleRedirect($user);
            return; 
        }

        $this->render('auth/login', ['error' => 'Invalid email/username or password', 'csrf_token' => $this->csrfToken()]);
    }

    public function logout(): void
    {
        session_destroy();
        $this->redirect('/login');
    }

    //

    public function register(): void
    {
        $this->render('auth/register');
    }
}