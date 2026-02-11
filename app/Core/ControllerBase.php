<?php
// src/Http/BaseController.php

declare(strict_types=1);
namespace App\Core;

abstract class ControllerBase
{
    protected ?\App\Repositories\UserRepository $userRepository = null;

    public function __construct(?\App\Repositories\UserRepository $userRepository = null)
    {
        $this->userRepository = $userRepository;

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    // ---------- Views ----------
    protected function view(string $template, array $data = [], int $status = 200): void
    {
        http_response_code($status);
        extract($data, EXTR_SKIP);

        // Resolve view path from app directory (robust to casing)
        $base = dirname(__DIR__) . DIRECTORY_SEPARATOR;
        $candidates = [
            $base . 'Views' . DIRECTORY_SEPARATOR . ltrim($template, '/'),
            $base . 'views' . DIRECTORY_SEPARATOR . ltrim($template, '/'),
        ];

        $path = null;
        foreach ($candidates as $c) {
            $p = $c;
            if (!str_ends_with($p, '.php')) $p .= '.php';
            if (is_file($p)) { $path = $p; break; }
        }

        if ($path === null) {
            $this->abort(500, "View not found: {$template}");
        }

        require $path;
        exit;
    }

    // Alias used by controllers
    protected function render(string $template, array $data = [], int $status = 200): void
    {
        $this->view($template, $data, $status);
    }

    // ---------- JSON ----------
    protected function json(mixed $payload, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    // ---------- Redirect ----------
    protected function redirect(string $to, int $status = 302): void
    {
        header('Location: ' . $to, true, $status);
        exit;
    }

    // ---------- Request helpers ----------
    protected function method(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    protected function isPost(): bool
    {
        return $this->method() === 'POST';
    }

    protected function input(string $key, mixed $default = null): mixed
    {
        // POST first, then GET
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    protected function requireFields(array $keys): void
    {
        foreach ($keys as $k) {
            $v = $this->input($k, null);
            if ($v === null || (is_string($v) && trim($v) === '')) {
                $this->abort(422, "Missing field: {$k}");
            }
        }
    }

    protected function int(string $key, int $default = 0): int
    {
        $v = $this->input($key, null);
        return ($v === null) ? $default : (int)$v;
    }

    protected function str(string $key, string $default = ''): string
    {
        $v = $this->input($key, null);
        return ($v === null) ? $default : trim((string)$v);
    }

    // ---------- Auth helpers ----------
    protected function userId(): ?int
    {
        return isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
    }

    protected function userRole(): ?string
    {
        return isset($_SESSION['role']) ? (string)$_SESSION['role'] : null;
    }

    protected function requireLogin(): void
    {
        if ($this->userId() === null) {
            $this->redirect('/login');
        }
    }

    protected function requireRole(string ...$roles): void
    {
        $role = $this->userRole();
        if ($role === null || !in_array($role, $roles, true)) {
            $this->abort(403, 'Forbidden');
        }
    }

    // ---------- CSRF (simple) ----------
    protected function csrfToken(): string
    {
        if (empty($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }
        return (string)$_SESSION['_csrf'];
    }

    protected function verifyCsrf(): void
    {
        if ($this->method() !== 'POST') return;

        $token = (string)($_POST['_csrf'] ?? '');
        if ($token === '' || !hash_equals((string)($_SESSION['_csrf'] ?? ''), $token)) {
            $this->abort(419, 'CSRF token mismatch');
        }
    }

    // Redirect users after login based on role
    protected function handleRedirect(\App\Models\User $user): void
    {
        $role = $user->role->value ?? '';
        switch ($role) {
            case 'admin':
                $this->redirect('/admin');
                break;
            case 'employee':
                $this->redirect('/employee');
                break;
            default:
                $this->redirect('/');
                break;
        }
    }

    // ---------- Errors ----------
    protected function abort(int $status, string $message = 'Error'): void
    {
        http_response_code($status);

        // If request expects JSON
        $accept = strtolower($_SERVER['HTTP_ACCEPT'] ?? '');
        if (str_contains($accept, 'application/json')) {
            $this->json(['error' => $message], $status);
        }

        echo "<h1>{$status}</h1><p>" . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . "</p>";
        exit;
    }
}
