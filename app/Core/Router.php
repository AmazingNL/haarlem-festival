<?php
declare(strict_types=1);

namespace App\Core;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\cachedDispatcher;
use App\Core\Middleware;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\AdminPageController;
use App\Repositories\AdminPageRepository;
use App\Services\AdminPageService;
use App\Services\UserService;
use App\Repositories\UserRepository;

final class Router
{
    public function dispatch(): void
    {
        [$httpMethod, $path] = $this->getRequestMethodAndPath();

        $this->startSessionForPath($path);

        $dispatcher = $this->buildDispatcher();

        $routeInfo = $dispatcher->dispatch($httpMethod, $path);

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                $this->respond(404, '404 - Page not found');
                return;

            case Dispatcher::METHOD_NOT_ALLOWED:
                $this->respond(405, '405 - Method not allowed');
                return;

            case Dispatcher::FOUND:
                [$class, $method] = $routeInfo[1];
                $vars = $routeInfo[2];

                if (!class_exists($class)) {
                    $this->respond(500, 'Controller not found');
                    return;
                }

                if ($this->isAdminPath($path)) {
                    Middleware::requireAdmin();
                }

                if ($class === AdminPageController::class) {
                    $repo = new AdminPageRepository();
                    $service = new AdminPageService($repo);
                    $controller = new $class($service);
                } 
                elseif ($class === AuthController::class) {
                    $repo = new UserRepository();
                    $service = new UserService($repo);
                    $controller = new $class($service);
                }
                else {
                    $controller = new $class();
                }

                if (!method_exists($controller, $method)) {
                    $this->respond(500, 'Method not found');
                    return;
                }

                call_user_func_array([$controller, $method], array_values($vars));
                return;
        }
    }

    private function buildDispatcher(): Dispatcher
    {
        $cacheFile = __DIR__ . '/../../storage/cache/routes.cache.php';
        $cacheDisabled = ($_ENV['APP_DEBUG'] ?? 'false') === 'true';

        return cachedDispatcher(function (RouteCollector $r) {
            // admin route 

            $r->get('/admin/login', [AuthController::class, 'showLogin']);
            $r->post('/admin/login', [AuthController::class, 'login']);
            $r->get('/admin/logout', [AuthController::class, 'logout']);
            $r->get('/admin/dashboard', [AdminPageController::class, 'index']);

            //app route
            $r->get('/login', [AuthController::class, 'showLogin']);
            $r->post('/login', [AuthController::class, 'login']);
            $r->get('/logout', [AuthController::class, 'logout']);

            $r->get('/', [HomeController::class, 'index']);

            // Add more routes as needed
        }, [
            'cacheFile' => $cacheFile,
            'cacheDisabled' => $cacheDisabled,
        ]);
    }

    private function startSessionForPath(string $path): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        // Separate cookie names
        if ($this->isAdminPath($path)) {
            session_name('HF_ADMIN');
        } else {
            session_name('HF_APP');
        }

        session_set_cookie_params([
            'httponly' => true,
            'secure' => $this->isHttps(),
            'samesite' => 'Lax',
            'path' => '/',
        ]);

        session_start();
    }

    private function getRequestMethodAndPath(): array
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        return [$method, rawurldecode($path)];
    }

    private function isAdminPath(string $path): bool
    {
        if (str_starts_with($path, '/admin')) {
            Middleware::requireAdmin();
            return true;
        }

        return false;
    }


    private function isHttps(): bool
    {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || ($_SERVER['SERVER_PORT'] ?? 80) == 443;
    }

    private function respond(int $code, string $message): void
    {
        http_response_code($code);
        echo $message;
    }
}
