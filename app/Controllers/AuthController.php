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

    public function showLoginForm(): void
    {
        $this->view('auth/login');
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
                $this->redirect('/customer/dashboard');
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
        // Close any session that might already be open (important)
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }

        session_name($sessionName);
        session_start();

        // Prevent session fixation after login
        session_regenerate_id(true);
    }
}
