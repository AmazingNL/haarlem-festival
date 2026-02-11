<?php
declare(strict_types=1);

namespace App\Core;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

final class Router
{
    public function dispatch(): void
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $r) {
            $r->addRoute('GET',  '/login',    [\App\Controllers\AuthController::class, 'login']);
            $r->addRoute('POST', '/login',    [\App\Controllers\AuthController::class, 'processLogin']);
            $r->addRoute('GET',  '/logout',   [\App\Controllers\AuthController::class, 'logout']);
            $r->addRoute('GET',  '/register', [\App\Controllers\AuthController::class, 'register']);
        });

        $httpMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri        = $_SERVER['REQUEST_URI'] ?? '/';

        $pos = strpos($uri, '?');
        if ($pos !== false) {
            $uri = substr($uri, 0, $pos);
        }

        $uri = rawurldecode($uri);

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                http_response_code(404);
                echo '404 - Page not found';
                return;

            case Dispatcher::METHOD_NOT_ALLOWED:
                http_response_code(405);
                echo '405 - Method not allowed';
                return;

            case Dispatcher::FOUND:
                [$class, $method] = $routeInfo[1];
                $vars = $routeInfo[2];

                if (!class_exists($class)) {
                    http_response_code(500);
                    echo 'Controller not found: ' . htmlspecialchars($class);
                    return;
                }

                // Manual DI for AuthController
                if ($class === \App\Controllers\AuthController::class) {
                    $pdo = $GLOBALS['pdo'];
                    $userRepo = new \App\Repositories\UserRepository($pdo);
                    $userService = new \App\Services\UserService($userRepo);
                    $controller = new $class($userService);
                } else {
                    $controller = new $class();
                }

                call_user_func_array([$controller, $method], array_values($vars));
                return;
        }
    }
}
