<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Enum\UserRole;
use App\Models\User;
use App\Services\Interfaces\IUserService;
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
        $this->ensureSession();

        $this->view('auth/register', [
            'title' => 'Registration',
            'next' => AuthRedirect::remember($this->str('next')),
        ], 'auth');
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
                $this->optionalPhone()
            );

            $this->loginUser($user, $this->str('next'));
        } catch (\InvalidArgumentException $e) {
            $this->setErrorMessage($e->getMessage());
            $this->redirect('/registerForm');
        } catch (\Throwable $e) {
            $this->setErrorMessage('Could not create your account right now.');
            $this->redirect('/registerForm');
        }
    }

    public function showLoginForm(): void
    {
        $this->ensureSession();

        $this->view('auth/login', [
            'title' => 'Login',
            'isAdminLogin' => $this->isAdminRoute(),
            'next' => AuthRedirect::remember($this->str('next')),
        ], 'auth');
    }

    public function login(): void
    {
        $loginForm = $this->isAdminRoute() ? '/admin/loginForm' : '/loginForm';

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
        $this->ensureSession();
        session_regenerate_id(true);

        SessionUser::storeInSession($user);

        $role = $user->role instanceof UserRole ? $user->role->value : strtolower((string) $user->role);
        if ($role === UserRole::admin->value) {
            $_SESSION['admin'] = true;
        }

        $this->redirect(AuthRedirect::targetAfterLogin($role, $requestedNext));
    }

    private function optionalPhone(): ?string
    {
        $phone = trim($this->str('phone'));
        return $phone === '' ? null : $phone;
    }

    private function isAdminRoute(): bool
    {
        return str_starts_with($_SERVER['REQUEST_URI'] ?? '', '/admin');
    }
}
