<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Enum\UserRole;
use App\Models\User;
use App\Services\IUserService;

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

        $next = $this->getAuthRedirect();
        if ($next !== '') {
            $_SESSION['auth_redirect'] = $next;
        }

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

            $firstName = $this->str('first_name');
            $lastName = $this->str('last_name');
            $username = $this->str('username');
            $phone = $this->readPhoneNumber();
            $next = trim((string) $this->input('next', ''));

            $user = new User($username, $email, $password, $firstName, $lastName, $phone, UserRole::customer);

            $this->ensureUserIsUnique($user);
            $this->userService->registerUser($user, $password);
            $this->startUserSession($user, $next);
        } catch (\Throwable $e) {
            $this->setErrorMessage('Could not create your account right now.');
            $this->redirect('/registerForm');
        }
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

    private function ensureUserIsUnique(User $user): void
    {
        if ($this->userService->userExists($user->email, $user->username)) {
            $this->abort(409, 'Email or username already exists');
        }
    }

    public function showLoginForm(): void
    {
        $this->ensureSession();

        $isAdminLogin = str_starts_with($_SERVER['REQUEST_URI'] ?? '', '/admin');
        $next = $this->getAuthRedirect();
        if ($next !== '') {
            $_SESSION['auth_redirect'] = $next;
        }

        $this->view('auth/login', [
            'title' => 'Login',
            'isAdminLogin' => $isAdminLogin,
            'next' => $next,
        ], 'auth');
    }

    public function login(): void
    {
        $loginForm = str_starts_with($_SERVER['REQUEST_URI'] ?? '', '/admin')
            ? '/admin/loginForm'
            : '/loginForm';

        try {
            $this->ensureSession();
            $this->verifyCsrf();
            $this->requireFields(['login', 'password']);

            $login = trim($this->str('login'));
            $password = $this->str('password');
            $next = trim((string) $this->input('next', ''));

            $user = $this->userService->authenticate($login, $password);
            if ($user === null) {
                $this->setErrorMessage('Invalid email/username or password.');
                $this->redirect($loginForm);
                return;
            }

            $this->startUserSession($user, $next);
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

        $roleValue = $user->role instanceof UserRole
            ? $user->role->value
            : strtolower((string) $user->role);

        $_SESSION['user_id'] = $user->user_id;
        $_SESSION['user_role'] = $roleValue;
        $_SESSION['role'] = $roleValue;
        $_SESSION['user_first_name'] = $user->first_name;
        $_SESSION['user_last_name'] = $user->last_name;
        $_SESSION['user_name'] = trim($user->first_name . ' ' . $user->last_name);
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_phone'] = $user->phone;

        $isAdmin = ($roleValue === UserRole::admin->value);
        if ($isAdmin) {
            $_SESSION['admin'] = true;
            unset($_SESSION['auth_redirect']);
            $this->redirect('/admin/dashboard');
            return;
        }

        unset($_SESSION['admin']);

        $redirectTarget = $this->cleanRedirectPath(
            $requestedRedirect !== '' ? $requestedRedirect : $this->getAuthRedirect()
        );

        if ($redirectTarget !== '') {
            unset($_SESSION['auth_redirect']);
            $this->redirect($redirectTarget);
            return;
        }

        switch ($roleValue) {
            case UserRole::employee->value:
                $this->redirect('/employee/dashboard');
                break;
            case UserRole::customer->value:
            default:
                $this->redirect('/');
                break;
        }
    }

    private function getAuthRedirect(): string
    {
        $this->ensureSession();

        $requested = trim((string) $this->input('next', ''));
        if ($requested !== '') {
            return $this->cleanRedirectPath($requested);
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

    private function readPhoneNumber(): ?string
    {
        $phone = trim((string) ($_POST['phone'] ?? ''));
        return $phone === '' ? null : $phone;
    }
}
