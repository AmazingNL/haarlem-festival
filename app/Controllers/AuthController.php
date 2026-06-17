<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Enum\UserRole;
use App\Models\User;
use App\Services\IAccountEmailService;
use App\Services\IUserService;
use App\Support\SessionUser;

final class AuthController extends BaseController
{
    private IUserService $userService;
    private IAccountEmailService $accountEmailService;

    public function __construct(IUserService $userService, IAccountEmailService $accountEmailService)
    {
        $this->userService = $userService;
        $this->accountEmailService = $accountEmailService;
    }

    public function showRegisterForm(): void
    {
        $this->ensureSession();
        $next = $this->rememberAuthRedirect();

        $this->view('auth/register', [
            'title' => 'Registration',
            'next' => $next,
        ], 'auth');
    }

    public function register(): void
    {
        try {
            $this->verifyCsrf();
            $this->requireFields(['first_name', 'last_name', 'username', 'email', 'password']);

            $email = $this->str('email');
            $password = $this->str('password');
            $this->validateRegistrationInput($email, $password);

            $user = new User(
                $this->str('username'),
                $email,
                $password,
                $this->str('first_name'),
                $this->str('last_name'),
                $this->readPhoneNumber(),
                UserRole::customer
            );

            if ($this->userService->userExists($user->email, $user->username)) {
                $this->abort(409, 'Email or username already exists');
            }

            $this->userService->registerUser($user, $password);
            $this->sendWelcomeEmail($user);
            $this->startUserSession($user, trim((string) $this->input('next', '')));
        } catch (\Throwable $e) {
            $this->setErrorMessage('Could not create your account right now.');
            $this->redirect('/registerForm');
        }
    }

    public function showLoginForm(): void
    {
        $this->ensureSession();
        $next = $this->rememberAuthRedirect();

        $this->view('auth/login', [
            'title' => 'Login',
            'isAdminLogin' => $this->isAdminRoute(),
            'next' => $next,
        ], 'auth');
    }

    public function login(): void
    {
        $loginForm = $this->isAdminRoute() ? '/admin/loginForm' : '/loginForm';

        try {
            $this->ensureSession();
            $this->verifyCsrf();
            $this->requireFields(['login', 'password']);

            $user = $this->userService->authenticate(
                trim($this->str('login')),
                $this->str('password')
            );

            if ($user === null) {
                $this->setErrorMessage('Invalid email/username or password.');
                $this->redirect($loginForm);
                return;
            }

            $this->startUserSession($user, trim((string) $this->input('next', '')));
        } catch (\Throwable $e) {
            $this->setErrorMessage('Something went wrong.');
            $this->redirect($loginForm);
        }
    }

    public function logout(): void
    {
        $this->ensureSession();
        $wasAdmin = !empty($_SESSION['admin']);

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }

        $this->redirect($wasAdmin ? '/admin/loginForm' : '/loginForm');
    }

    private function startUserSession(User $user, string $requestedRedirect = ''): void
    {
        $this->ensureSession();
        session_regenerate_id(true);

        SessionUser::storeInSession($user);

        $roleValue = $user->role instanceof UserRole
            ? $user->role->value
            : strtolower((string) $user->role);

        if ($roleValue === UserRole::admin->value) {
            $_SESSION['admin'] = true;
            unset($_SESSION['auth_redirect']);
            $this->redirect('/admin/dashboard');
            return;
        }

        unset($_SESSION['admin']);

        $redirectTarget = $this->cleanRedirectPath(
            $requestedRedirect !== '' ? $requestedRedirect : (string) ($_SESSION['auth_redirect'] ?? '')
        );

        if ($redirectTarget !== '') {
            unset($_SESSION['auth_redirect']);
            $this->redirect($redirectTarget);
            return;
        }

        if ($roleValue === UserRole::employee->value) {
            $this->redirect('/employee/dashboard');
            return;
        }

        $this->redirect('/');
    }

    private function rememberAuthRedirect(): string
    {
        $requested = trim((string) $this->input('next', ''));
        if ($requested !== '') {
            $clean = $this->cleanRedirectPath($requested);
            if ($clean !== '') {
                $_SESSION['auth_redirect'] = $clean;
            }
            return $clean;
        }

        return $this->cleanRedirectPath((string) ($_SESSION['auth_redirect'] ?? ''));
    }

    private function cleanRedirectPath(string $path): string
    {
        $path = trim($path);
        if ($path === '' || !str_starts_with($path, '/')) {
            return '';
        }

        return str_starts_with($path, '/admin') ? '' : $path;
    }

    private function isAdminRoute(): bool
    {
        return str_starts_with($_SERVER['REQUEST_URI'] ?? '', '/admin');
    }

    private function validateRegistrationInput(string $email, string $password): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->abort(422, 'Invalid email address');
        }

        if (mb_strlen($password) < 8) {
            $this->abort(422, 'Password must be at least 8 characters');
        }
    }

    private function readPhoneNumber(): ?string
    {
        $phone = trim((string) ($_POST['phone'] ?? ''));
        return $phone === '' ? null : $phone;
    }

    /**
     * Send the welcome email as a best-effort side effect: a mail failure must
     * never roll back an account that was already created successfully.
     */
    private function sendWelcomeEmail(User $user): void
    {
        try {
            $this->accountEmailService->sendWelcome($user);
        } catch (\Throwable $e) {
            error_log('Welcome email failed for ' . $user->email . ': ' . $e->getMessage());
        }
    }
}
