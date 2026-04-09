<?php
// src/Http/BaseController.php

declare(strict_types=1);
namespace App\Core;
abstract class BaseController
{
    // ---------- Views ----------
    protected function view(string $template, array $data = [], ?string $layout = 'main', int $status = 200): void
    {
        $data['csrf'] ??= $this->csrfToken();
        // inject any flash messages stored in session 
        $data['flash'] = $data['flash'] ?? $this->getAllFlash();
        extract($data, EXTR_SKIP);

            $content = __DIR__ . '/../Views/' . $template. '.php';
        if (!is_file($content)) {
            $this->abort(500, "View not found: {$template}");
        }

            $layout = __DIR__ . '/../Views/layout/' . ltrim($layout, '/') . '.php';
        if (!is_file($layout)) {
            $this->abort(500, "Layout not found: {$layout}");
        }

        require $layout;
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
    protected function redirect(string $to, $status = 302): void
    {
        $statusCode = (int) $status;
        if (!headers_sent()) {
            header('Location: ' . $to, true, $statusCode);
            exit;
        }
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

    protected function currentUserId(): ?int
    {
        $this->ensureSession();
        return isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
    }

    protected function currentUserRole(): ?string
    {
        $this->ensureSession();
        return isset($_SESSION['user_role']) ? (string) $_SESSION['user_role'] : null;
    }

    protected function isLoggedIn(): bool
    {
        return $this->currentUserId() !== null;
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
        return ($v === null) ? $default : (int) $v;
    }

    protected function str(string $key, string $default = ''): string
    {
        $v = $this->input($key, null);
        return ($v === null) ? $default : trim((string) $v);
    }

    protected function rememberProgramReturnUrl(string $url): void
    {
        $this->ensureSession();

        $cleanUrl = $this->cleanInternalUrl($url);
        if ($cleanUrl === '') {
            return;
        }

        $pathOnly = (string) (parse_url($cleanUrl, PHP_URL_PATH) ?? '');
        $blockedPaths = ['/program', '/checkout', '/orders', '/loginForm', '/registerForm', '/logout'];

        foreach ($blockedPaths as $blockedPath) {
            if (str_starts_with($pathOnly, $blockedPath)) {
                return;
            }
        }

        $_SESSION['program_return_url'] = $cleanUrl;
    }

    protected function getProgramReturnUrl(string $default = '/home'): string
    {
        $this->ensureSession();

        $savedUrl = $this->cleanInternalUrl((string) ($_SESSION['program_return_url'] ?? ''));
        if ($savedUrl !== '') {
            return $savedUrl;
        }

        $defaultUrl = $this->cleanInternalUrl($default);
        return $defaultUrl !== '' ? $defaultUrl : '/home';
    }

    protected function currentUrl(): string
    {
        return $this->cleanInternalUrl((string) ($_SERVER['REQUEST_URI'] ?? ''));
    }

    protected function cleanInternalUrl(string $url): string
    {
        $url = trim($url);
        if ($url === '' || !str_starts_with($url, '/')) {
            return '';
        }

        $parts = parse_url($url);
        if ($parts === false) {
            return '';
        }

        $path = (string) ($parts['path'] ?? '');
        if ($path === '' || !str_starts_with($path, '/')) {
            return '';
        }

        $cleanUrl = $path;
        if (!empty($parts['query'])) {
            $cleanUrl .= '?' . $parts['query'];
        }

        return $cleanUrl;
    }

    // ---------- Auth helpers ----------

    // protected function userId(): ?int
    // {
    //     return isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
    // }

    // protected function adminId(): ?int
    // {
    //     return isset($_SESSION['admin_user_id']) ? (int) $_SESSION['admin_user_id'] : null;
    // }
    // protected function userRole(): ?string
    // {
    //     return isset($_SESSION['role']) ? (string) $_SESSION['role'] : null;
    // }

    // protected function requireLogin(): void
    // {
    //     if ($this->userId() === null) {
    //         $this->redirect('/login');
    //     }
    // }

    // ---------- CSRF ----------

    protected function ensureSession(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }
    protected function csrfToken(): string
    {
        $this->ensureSession();
        if (empty($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }
        return (string) $_SESSION['_csrf'];
    }

    protected function verifyCsrf(): void
    {
        if (!$this->isPost())
            return;

        $this->ensureSession();
        $token = (string) ($_POST['_csrf'] ?? '');
        if ($token === '' || !hash_equals((string) ($_SESSION['_csrf'] ?? ''), $token)) {
            $this->abort(419, 'CSRF token mismatch');
        }
    }

    // ---------- Flash messages (one-time session messages) ----------
    protected function setFlash(string $key, mixed $value): void
    {
        $this->ensureSession();
        $_SESSION['_flash'][$key] = $value;
    }

    protected function setErrorMessage(string $message): void
    {
        // Save one error message in the session so it can be shown after redirect.
        $this->setFlash('error', $message);
    }

    protected function setSuccessMessage(string $message): void
    {
        // Save one success message in the session so it can be shown after redirect.
        $this->setFlash('success', $message);
    }

    protected function getFlash(string $key): mixed
    {
        $this->ensureSession();
        if (!isset($_SESSION['_flash'][$key])) {
            return null;
        }
        $val = $_SESSION['_flash'][$key];
        unset($_SESSION['_flash'][$key]);
        return $val;
    }

    protected function getAllFlash(): array
    {
        $this->ensureSession();
        $all = $_SESSION['_flash'] ?? [];
        // clear all flash messages after reading
        unset($_SESSION['_flash']);
        return is_array($all) ? $all : [];
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
