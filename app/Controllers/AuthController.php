<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Services\IUserService;
use App\Models\User;
use App\Models\Enum\UserRole;

final class AuthController extends BaseController
{
    private IUserService $userService;
    public function __construct(IUserService $userService) {
        $this->userService = $userService;
    }

    //registration part from here
    public function showRegisterForm(): void
    {
        $this->view('auth/register',
        $data = ['title' => 'Registration'],
        $layout ='auth'  );
    }

    public function register(): void
    {
        if (!$this->isPost()) $this->abort(405, 'Method Not Allowed');
        $this->verifyCsrf();
        [$user, $plainPassword] = $this->hydrateRegistrationUser();
        $this->ensureRegistrationIsUnique($user);
        $created = $this->userService->registerUser($user, $plainPassword);
        $this->loginAndRedirect($created);
    }

    private function hydrateRegistrationUser(): array
    {
        $this->requireFields(['first_name', 'last_name', 'username', 'email', 'password']);

        $email = $this->str('email');
        $password = $this->str('password');

        $this->validateRegistrationInput($email, $password);

        $user = new User();
        $user->first_name = $this->str('first_name');
        $user->last_name  = $this->str('last_name');
        $user->username   = $this->str('username');
        $user->email      = $email;
        $user->phone      = isset($_POST['phone']) ? trim((string)$_POST['phone']) : null;
        $user->role       = UserRole::customer;

        return [$user, $password];
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

    private function ensureRegistrationIsUnique(User $user): void
    {
        if (method_exists($this->userService, 'userExists')
            && $this->userService->userExists($user->email, $user->username)) {
            $this->abort(409, 'Email or username already exists');
        }
    }

    //login part from here
    public function showLogin(): void
    {
        $this->view('auth/login', $data =
        ['title' => 'Login'],
        layout: 'auth');
    }

    public function login(): void
    {
        if (!$this->isPost()) {
            $this->abort(405, 'Method Not Allowed');
        }

        $this->verifyCsrf();
        $this->requireFields(['email_or_Username', 'password']);

        $emailOrUsername = $this->str('email_or_Username');
        $password = $this->str('password');

        $user = $this->userService->authenticate($emailOrUsername, $password);
        if ($user === null) {
            $this->abort(401, 'Invalid credentials');
        }

        $this->loginAndRedirect($user);
    }

    public function logout(): void
    {
        // Destroys whichever session is currently active (HF_APP or HF_ADMIN)
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }

        $this->redirect('/login');
    }

    private function loginAndRedirect(User $user): void
    {
        // Assumes $user->role is an enum UserRole
        $isAdmin = ($user->role === UserRole::admin);

        // Switch to correct session cookie jar
        $this->switchSession($isAdmin ? 'HF_ADMIN' : 'HF_APP');

        // Store login info in that session only
        $_SESSION['user_id'] = $user->user_id;
        $_SESSION['role']    = $user->role->value;

        // Redirect
        if ($isAdmin) {
            $this->redirect('/admin/dashboard');
            return;
        }

        switch ($user->role) {
            case UserRole::customer:
                $this->redirect('/loginForm');
                return;

            case UserRole::employee:
                $this->redirect('/employee/dashboard');
                return;

            default:
                $this->redirect('/login');
                return;
        }
    }

    private function switchSession(string $sessionName): void
    {
        // Close any session that might already be open
        $this->ensureSession();
        session_write_close();

        session_name($sessionName);
        session_start();

        // Prevent session fixation after login
        session_regenerate_id(true);
    }
}
