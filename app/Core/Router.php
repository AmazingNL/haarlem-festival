<?php
declare(strict_types=1);

namespace App\Core;

use App\Repositories\ImageRepository;
use App\Services\ImageService;
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
use App\Repositories\PageSectionRepository;
use App\Services\PageSectionService;

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

                $adminPublic = [
                    '/admin/loginForm',
                    '/admin/login',
                    '/admin/register',
                ];

                if ($this->isAdminPath($path) && !in_array($path, $adminPublic, true)) {
                    Middleware::requireAdmin();
                }

                if ($class === AdminPageController::class) {
                    $pageRepo = new AdminPageRepository();
                    $pageService = new AdminPageService($pageRepo);
                    $userRepo = new UserRepository();
                    $userService = new UserService($userRepo);
                    $imageRepo = new ImageRepository();
                    $imageService = new ImageService($imageRepo);
                    $pageSectionRepo = new PageSectionRepository();
                    $pageSectionService = new PageSectionService($pageSectionRepo, $imageRepo);
                    $controller = new $class($pageService, $pageSectionService, $userService, $imageService);
                } elseif ($class === AuthController::class) {
                    $repo = new UserRepository();
                    $service = new UserService($repo);
                    $controller = new $class($service);
                } elseif ($class === HomeController::class) {
                    $pageRepo = new AdminPageRepository();
                    $pageservice = new AdminPageService($pageRepo);
                    $imageRepo = new ImageRepository();
                    $imageService = new ImageService($imageRepo);
                    $pageSectionRepo = new PageSectionRepository();
                    $pageSectionService = new PageSectionService($pageSectionRepo, $imageRepo);
                    $controller = new $class($pageSectionService, $pageservice);
                } else {
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

            $r->get('/admin/register', [AuthController::class, 'showRegisterForm']);
            $r->post('/admin/register', [AuthController::class, 'register']);
            $r->get('/admin/loginForm', [AuthController::class, 'showLogin']);
            $r->post('/admin/login', [AuthController::class, 'login']);
            $r->get('/admin/logout', [AuthController::class, 'logout']);
            $r->get('/admin', [AdminPageController::class, 'index']);
            $r->get('/admin/dashboard', [AdminPageController::class, 'index']);
            $r->get('/admin/dashboard/{page_id:\d+}/delete', [AdminPageController::class, 'deletePage']);
            $r->get('/admin/pages/createPage', [AdminPageController::class, 'createPageForm']);
            $r->post('/admin/pages/create', [AdminPageController::class, 'createPage']);
            $r->get('/admin/pages/{page_id:\d+}/editForm', [AdminPageController::class, 'editPageForm']);
            $r->post('/admin/pages/{page_id:\d+}/edit', [AdminPageController::class, 'editPage']);
            $r->get('/admin/pageSection/{page_id:\d+}/pageSectionForm', [AdminPageController::class, 'pageSectionForm']);
            $r->post('/admin/pageSection/{page_id:\d+}/createPage', [AdminPageController::class, 'createPageSection']);
            $r->get('/admin/pageSection/{page_id:\d+}/editSectionForm', [AdminPageController::class, 'editSectionForm']);
            $r->post('/admin/pageSection/{page_id:\d+}/editSection', [AdminPageController::class, 'editSection']);
            $r->get('/admin/pageSection/{page_id:\d+}/pageSectionList', [AdminPageController::class, 'pageSectionList']);
            $r->get('/admin/pages/viewPage', [AdminPageController::class, 'viewPages']);
            $r->get('/admin/pageSection/editPage', [AdminPageController::class, 'updatePageSection']);
            $r->get('/admin/users', [AdminPageController::class, 'manageUsersPage']);
            $r->get('/admin/events/{event_id:\d+}', [AdminPageController::class, 'viewEventPage']);
            $r->get('/admin/events/{event_id:\d+}/delete', [AdminPageController::class, 'deleteEventPage']);
            $r->get('/admin/events/{event_id:\d+}/edit', [AdminPageController::class, 'updateEventPage']);
            $r->post('/admin/media/upload', [AdminPageController::class, 'uploadImage']);



            //app route
            //app route
            $r->get('/registerForm', [AuthController::class, 'showRegisterForm']);
            $r->post('/register', [AuthController::class, 'register']);

            $r->get('/loginForm', [AuthController::class, 'showLogin']);
            $r->post('/login', [AuthController::class, 'login']);

            $r->get('/logout', [AuthController::class, 'logout']);

            $r->get('/', [HomeController::class, 'index']);
            $r->get('/home', [HomeController::class, 'index']);
            $r->get('/yummy', [HomeController::class, 'yummy']);




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
        return str_starts_with($path, '/admin');
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
