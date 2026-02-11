<?php
// src/Http/BaseController.php

declare(strict_types=1);
namespace App\Core;
abstract class BaseController
{
    // ---------- Views ----------
    protected function view(string $template, array $data = [], int $status = 200): void
    {
        http_response_code($status);
        extract($data, EXTR_SKIP);

        // Example: views/events/index.php
        $path = __DIR__ . '/../../views/' . ltrim($template, '/');
        if (!str_ends_with($path, '.php')) $path .= '.php';

        if (!is_file($path)) {
            $this->abort(500, "View not found: {$template}");
        }

        require $path;
        exit;
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
    protected function httpMethod(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    protected function isPost(): bool
    {
        return $this->httpMethod() === 'POST';
    }

    protected function input(string $key, mixed $default = null): mixed
    {
        // POST first, then GET
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    protected function requireFields(array $keys): void
    {
        foreach ($keys as $k) {
            $value = $this->input($k, null);
            if ($value === null || (is_string($value) && trim($value) === '')) {
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

    protected function adminId(): ?int
    {
        return isset($_SESSION['admin_user_id']) ? (int)$_SESSION['admin_user_id'] : null;
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
        if ($this->httpMethod() !== 'POST') return;

        $token = (string)($_POST['_csrf'] ?? '');
        if ($token === '' || !hash_equals((string)($_SESSION['_csrf'] ?? ''), $token)) {
            $this->abort(419, 'CSRF token mismatch');
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
