<?php

declare(strict_types=1);

namespace App\Core;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

class Router
{
    public function dispatch(): void
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $r) {


        });

        $httpMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = $_SERVER['REQUEST_URI'] ?? '/';

        if (false !== $pos = strpos($uri, '?'))
            $uri = substr($uri, 0, $pos);
        $uri = rawurldecode($uri);

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                http_response_code(404);
                echo "404 - Page not found";
                return;

            case Dispatcher::METHOD_NOT_ALLOWED:
                http_response_code(405);
                echo "405 - Method not allowed";
                return;

            case Dispatcher::FOUND:
                [$class, $method] = $routeInfo[1];
                $vars = $routeInfo[2];

                if (!class_exists($class)) {
                    http_response_code(500);
                    echo "Controller not found: " . htmlspecialchars($class);
                    return;
                }

                // Build controller (inject dependencies when needed)
                switch ($class) {               
                    default:
                        $controller = new $class();
                        break;
                }

               /* if (!method_exists($controller, $method)) {
                    http_response_code(500);
                    echo "Method not found: " . htmlspecialchars($class . '::' . $method);
                    return;
                }

                // Ensure session exists
                if (session_status() !== PHP_SESSION_ACTIVE) {
                    session_start();
                }

                // Protect all admin routes
                $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
                if (str_starts_with($path, '/admin')) {
                    \App\Core\Middleware::requireAdmin();
                }
                    */


                call_user_func_array([$controller, $method], array_values($vars));
                return;

        }
    }
}
