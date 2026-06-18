<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\User;
use App\Services\IUserService;
use App\Support\AuthRedirect;
use App\Support\SessionUser;

final class AuthController extends BaseController
{
    private IUserService $userService;

    public function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }

    public function showRegisterForm(): void
    {
        $this->showAuthForm('auth/register', 'Registration');
    }

    public function register(): void
    {
        try {
            $this->verifyCsrf();
            $this->requireFields(['first_name', 'last_name', 'username', 'email', 'password']);

            $user = $this->userService->registerCustomer(
                $this->str('first_name'),
                $this->str('last_name'),
                $this->str('username'),
                $this->str('email'),
                $this->str('password'),
                $this->str('phone')
            );

            $this->loginUser($user, $this->str('next'));
        } catch (\InvalidArgumentException $e) {
            $this->setErrorMessage($e->getMessage());
            $this->redirect('/registerForm');
        } catch (\Throwable $e) {
            error_log('Registration failed: ' . $e->getMessage());
            $this->setErrorMessage('Could not create your account right now.');
            $this->redirect('/registerForm');
        }
    }

    public function showLoginForm(): void
    {
        $this->showAuthForm('auth/login', 'Login');
    }

    public function login(): void
    {
        $loginForm = $this->loginFormPath();

        try {
            $this->ensureSession();
            $this->verifyCsrf();
            $this->requireFields(['login', 'password']);

            $user = $this->userService->authenticate($this->str('login'), $this->str('password'));
            if ($user === null) {
                $this->setErrorMessage('Invalid email/username or password.');
                $this->redirect($loginForm);
                return;
            }

            $this->loginUser($user, $this->str('next'));
        } catch (\Throwable $e) {
            error_log('Login failed: ' . $e->getMessage());
            $this->setErrorMessage('Something went wrong.');
            $this->redirect($loginForm);
        }
    }

    public function logout(): void
    {
        $this->ensureSession();
        $wasAdmin = !empty($_SESSION['admin']);

        session_unset();
        session_destroy();

        $this->redirect($wasAdmin ? '/admin/loginForm' : '/loginForm');
    }

    private function loginUser(User $user, string $requestedNext = ''): void
    {
        $role = SessionUser::beginAuthenticatedSession($user);
        $this->redirect(AuthRedirect::targetAfterLogin($role, $requestedNext));
    }

    private function showAuthForm(string $view, string $title): void
    {
        $this->ensureSession();

        $this->view($view, [
            'title' => $title,
            'next' => AuthRedirect::remember($this->str('next')),
            'formAction' => $this->authAction($view === 'auth/login' ? 'login' : 'register'),
            'loginPath' => '/loginForm',
            'registerPath' => '/registerForm',
        ], 'auth');
    }

    private function authAction(string $action): string
    {
        return ($this->isAdminRoute() ? '/admin' : '') . '/' . $action;
    }

    private function loginFormPath(): string
    {
        return $this->isAdminRoute() ? '/admin/loginForm' : '/loginForm';
    }

    private function isAdminRoute(): bool
    {
        return str_starts_with($_SERVER['REQUEST_URI'] ?? '', '/admin');
    }
}
