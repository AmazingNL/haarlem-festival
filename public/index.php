<?php
declare(strict_types=1);


use FastRoute\RouteCollector;
use FastRoute\Dispatcher;
use function FastRoute\simpleDispatcher;

use App\Controllers\AuthController;
use App\Controllers\AdminPageController;
use App\Controllers\HomeController;
use App\Controllers\HistoryController;
use App\Controllers\DancePageController;

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Models/Enum.php';
require_once __DIR__ . '/../app/config.php';

session_start();

$envFile = __DIR__ . '/../.env';
if (is_file($envFile)) {
    $env = parse_ini_file($envFile, false, INI_SCANNER_RAW);
    foreach ($env as $k => $v) {
        $_ENV[$k] = $v;
    }
}

$debug = ($_ENV['APP_DEBUG'] ?? 'false') === 'true';
error_reporting(E_ALL);
ini_set('display_errors', $debug ? '1' : '0');


$dispatcher = simpleDispatcher(function (RouteCollector $r) {
    // Admin Register Routing
    $r->get('/admin/register', [AuthController::class, 'showRegisterForm']);
    $r->post('/admin/register', [AuthController::class, 'register']);
    $r->get('/admin/loginForm', [AuthController::class, 'showLogin']);

    // Admin Login/Logout Routing
    $r->post('/admin/login', [AuthController::class, 'login']);
    $r->get('/admin/logout', [AuthController::class, 'logout']);

    // Admin dashboard Routing
    $r->get('/admin', [AdminPageController::class, 'index']);
    $r->get('/admin/dashboard', [AdminPageController::class, 'index']);
    $r->get('/admin/dashboard/{page_id:\d+}/delete', [AdminPageController::class, 'deletePage']);

    // Admin create Routing
    $r->get('/admin/pages/createPage', [AdminPageController::class, 'createPageForm']);
    $r->post('/admin/pages/create', [AdminPageController::class, 'createPage']);

    // Admin edit Routing
    $r->get('/admin/pages/{page_id:\d+}/editForm', [AdminPageController::class, 'editPageForm']);
    $r->post('/admin/pages/{page_id:\d+}/edit', [AdminPageController::class, 'editPage']);

    // Admin view Routing
    $r->get('/admin/pages/viewPage', [AdminPageController::class, 'viewPages']);

    // Admin Page Section Routing
    $r->get('/admin/pageSection/{page_id:\d+}/pageSectionForm', [AdminPageController::class, 'pageSectionForm']);
    $r->post('/admin/pageSection/{page_id:\d+}/createPage', [AdminPageController::class, 'createPageSection']);
    $r->get('/admin/pageSection/render-fields', [AdminPageController::class, 'renderSectionForm']);
    $r->get('/admin/pageSection/{page_id:\d+}/editSectionForm', [AdminPageController::class, 'editSectionForm']);
    $r->post('/admin/pageSection/{section_id:\d+}/editSection', [AdminPageController::class, 'editSection']);
    $r->get('/admin/pageSection/{page_id:\d+}/viewPageSections', [AdminPageController::class, 'viewPageSections']);
    $r->get('/admin/pageSection/editPage', [AdminPageController::class, 'updatePageSection']);
    $r->get('/admin/pageSection/{section_id:\d+}/deleteSection', [AdminPageController::class, 'deleteSection']);

    // Admin users Routing
    $r->get('/admin/users', [AdminPageController::class, 'manageUsersPage']);

    // Admin events Routing
    $r->get('/admin/events/{event_id:\d+}', [AdminPageController::class, 'viewEventPage']);
    $r->get('/admin/events/{event_id:\d+}/delete', [AdminPageController::class, 'deleteEventPage']);
    $r->get('/admin/events/{event_id:\d+}/edit', [AdminPageController::class, 'updateEventPage']);

    // Admin Media Routing
    $r->post('/admin/media/upload', [AdminPageController::class, 'uploadImage']);

    // Register Routing
    $r->get('/registerForm', [AuthController::class, 'showRegisterForm']);
    $r->post('/register', [AuthController::class, 'register']);

    // Login/logout Routing
    $r->get('/loginForm', [AuthController::class, 'showLogin']);
    $r->post('/login', [AuthController::class, 'login']);
    $r->get('/logout', [AuthController::class, 'logout']);

    // Home page Routing
    $r->get('/', [HomeController::class, 'index']);
    $r->get('/home', [HomeController::class, 'index']);

    // AmazingGrace "Restuarant Events" Routing
    $r->get('/yummy', [HomeController::class, 'yummy']);
    $r->get('/yummy/ratatouille', [HomeController::class, 'ratatouille']);

    // Nokus "Stories Events" Routing
    $r->get('/stories', [HomeController::class, 'stories']);
    //$r->get('/stories/{slug}', [HomeController::class, 'storyDetail']);

    // Darlington "History Events" Routing
    $r->get('/history', [HistoryController::class, 'index']);
    $r->get('/history/book-tour', [HistoryController::class, 'bookTour']);
    $r->post('/history/book-tour/add-to-program', [HistoryController::class, 'addBookTourToProgram']);
    $r->get('/history/route-map', [HistoryController::class, 'routeMap']);
    $r->get('/history/st-bavos-church', [HistoryController::class, 'stBavosChurch']);
    $r->get('/history/molen-de-adriaan', [HistoryController::class, 'molenDeAdriaan']);
    $r->get('/program', [HistoryController::class, 'program']);
    $r->post('/program/remove', [HistoryController::class, 'removeProgramItem']);

    // JD "Dance Events" Routing
    $r->get('/dance', [DancePageController::class, 'dance']);
});


$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$publicAdminRoutes = [
    '/admin/loginForm',
    '/admin/login',
    '/admin/register',
    '/admin/logout',
];

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {

    case Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo "404 - Page not found";
        break;

    case Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo "405 - Method not allowed";
        break;

    case Dispatcher::FOUND:

        [$controllerClass, $method] = $routeInfo[1];
        $vars = $routeInfo[2];

        if (
            str_starts_with($uri, '/admin')
            && !in_array($uri, $publicAdminRoutes)
            && empty($_SESSION['admin'])
        ) {
            header('Location: /admin/loginForm');
            exit;
        }

        $controller = createController($controllerClass);
        // FastRoute provides associative params; pass positionally to avoid PHP named-arg binding.
        call_user_func_array([$controller, $method], array_values($vars));

        break;
}

function createController(string $controllerClass)
{
    switch ($controllerClass) {

        // Prepare Controller Method "Home"
        case App\Controllers\HomeController::class:

            $pageRepo = new App\Repositories\AdminPageRepository();
            $pageService = new App\Services\AdminPageService($pageRepo);

            $imageRepo = new App\Repositories\ImageRepository();
            $imageService = new App\Services\ImageService($imageRepo);

            $sectionRepo = new App\Repositories\PageSectionRepository();
            $sectionService = new App\Services\PageSectionService($sectionRepo, $imageService);

            return new App\Controllers\HomeController($sectionService, $pageService);

            // Prepare Controller Method "History"
        case App\Controllers\HistoryController::class:

            $pageRepo = new App\Repositories\AdminPageRepository();
            $pageService = new App\Services\AdminPageService($pageRepo);

            $imageRepo = new App\Repositories\ImageRepository();
            $imageService = new App\Services\ImageService($imageRepo);

            $sectionRepo = new App\Repositories\PageSectionRepository();
            $sectionService = new App\Services\PageSectionService($sectionRepo, $imageService);

            return new App\Controllers\HistoryController($sectionService, $pageService);

            // Prepare Controller Method "Authorization"
        case App\Controllers\AuthController::class:

            $repo = new App\Repositories\UserRepository();
            $service = new App\Services\UserService($repo);

            return new App\Controllers\AuthController($service);

            // Prepare Controller Method "AdminPage"
        case App\Controllers\AdminPageController::class:

            $pageRepo = new App\Repositories\AdminPageRepository();
            $pageService = new App\Services\AdminPageService($pageRepo);

            $userRepo = new App\Repositories\UserRepository();
            $userService = new App\Services\UserService($userRepo);

            $imageRepo = new App\Repositories\ImageRepository();
            $imageService = new App\Services\ImageService($imageRepo);

            $sectionRepo = new App\Repositories\PageSectionRepository();
            $sectionService = new App\Services\PageSectionService($sectionRepo, $imageService);

            return new App\Controllers\AdminPageController(
                $pageService,
                $sectionService,
                $userService,
                $imageService
            );

            // Prepare Controller Method "Dance"
        case App\Controllers\DancePageController::class:

            $pageRepo = new App\Repositories\AdminPageRepository();
            $pageService = new App\Services\AdminPageService($pageRepo);

            $imageRepo = new App\Repositories\ImageRepository();
            $imageService = new App\Services\ImageService($imageRepo);

            $sectionRepo = new App\Repositories\PageSectionRepository();
            $sectionService = new App\Services\PageSectionService($sectionRepo, $imageService);

            return new App\Controllers\DancePageController($sectionService, $pageService);

        default:
            return new $controllerClass();
    }
}
