<?php
declare(strict_types=1);


use App\Controllers\YummyController;
use FastRoute\RouteCollector;
use FastRoute\Dispatcher;
use function FastRoute\simpleDispatcher;

use App\Controllers\AuthController;
use App\Controllers\AdminPageController;
use App\Controllers\EventController;
use App\Controllers\HomeController;
use App\Controllers\HistoryController;
use App\Controllers\ProgramController;
use App\Controllers\ShopController;

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

    $r->get('/admin/register', [AuthController::class, 'showRegisterForm']);
    $r->post('/admin/register', [AuthController::class, 'register']);

    $r->get('/admin/loginForm', [AuthController::class, 'showLoginForm']);
    $r->post('/admin/login', [AuthController::class, 'login']);

    $r->get('/admin/logout', [AuthController::class, 'logout']);


    $r->get('/admin', [AdminPageController::class, 'index']);
    $r->get('/admin/dashboard', [AdminPageController::class, 'index']);

    $r->get('/admin/dashboard/{page_id:\d+}/delete', [AdminPageController::class, 'deletePage']);


    $r->get('/admin/pages/createPage', [AdminPageController::class, 'createPageForm']);
    $r->post('/admin/pages/create', [AdminPageController::class, 'createPage']);

    $r->get('/admin/pages/{page_id:\d+}/editForm', [AdminPageController::class, 'editPageForm']);
    $r->post('/admin/pages/{page_id:\d+}/edit', [AdminPageController::class, 'editPage']);

    $r->get('/admin/pages/viewPage', [AdminPageController::class, 'viewPages']);


    $r->get('/admin/pageSection/{page_id:\d+}/pageSectionForm', [AdminPageController::class, 'pageSectionForm']);
    $r->post('/admin/pageSection/{page_id:\d+}/createPage', [AdminPageController::class, 'createPageSection']);
    $r->get('/admin/pageSection/render-fields', [AdminPageController::class, 'renderSectionForm']);

    $r->get('/admin/pageSection/{page_id:\d+}/editSectionForm', [AdminPageController::class, 'editSectionForm']);
    $r->post('/admin/pageSection/{section_id:\d+}/editSection', [AdminPageController::class, 'editSection']);

    $r->get('/admin/pageSection/{page_id:\d+}/viewPageSections', [AdminPageController::class, 'viewPageSections']);
    $r->get('/admin/pageSection/editPage', [AdminPageController::class, 'updatePageSection']);
    $r->get('/admin/pageSection/{section_id:\d+}/deleteSection', [AdminPageController::class, 'deleteSection']);


    $r->get('/admin/users', [AdminPageController::class, 'manageUsersPage']);

    $r->get('/admin/events/{event_id:\d+}', [AdminPageController::class, 'viewEventPage']);
    $r->get('/admin/events/{event_id:\d+}/delete', [AdminPageController::class, 'deleteEventPage']);
    $r->get('/admin/events/{event_id:\d+}/edit', [AdminPageController::class, 'updateEventPage']);


    $r->post('/admin/media/upload', [AdminPageController::class, 'uploadImage']);


    $r->get('/registerForm', [AuthController::class, 'showRegisterForm']);
    $r->post('/register', [AuthController::class, 'register']);

    $r->get('/loginForm', [AuthController::class, 'showLoginForm']);
    $r->post('/login', [AuthController::class, 'login']);

    $r->get('/logout', [AuthController::class, 'logout']);


    $r->get('/', [HomeController::class, 'index']);
    $r->get('/home', [HomeController::class, 'index']);
    $r->get('/events', [EventController::class, 'index']);
    $r->post('/events/add-to-program', [EventController::class, 'addToProgram']);
    $r->get('/checkout', [ShopController::class, 'checkout']);
    $r->post('/checkout/pay', [ShopController::class, 'pay']);
    $r->get('/checkout/success', [ShopController::class, 'checkoutSuccess']);
    $r->get('/checkout/cancel', [ShopController::class, 'checkoutCancel']);
    $r->get('/orders/{orderId:\d+}/success', [ShopController::class, 'success']);
    $r->get('/yummy', [YummyController::class, 'yummy']);
    $r->get('/yummy/ratatouille', [YummyController::class, 'ratatouille']);
    $r->post('/yummy/ratatouille/book-reservation', [YummyController::class, 'bookReservation']);
    $r->get('/yummy/bistro-toujours', [YummyController::class, 'bistroToujours']);
    $r->post('/yummy/bistro-toujours/book-reservation', [YummyController::class, 'bookBistroToujoursReservation']);

    $r->get('/stories', [HomeController::class, 'stories']);
    $r->get('/stories/{slug}', [HomeController::class, 'storyDetail']);
    $r->get('/history', [HistoryController::class, 'index']);
    $r->get('/history/book-tour', [HistoryController::class, 'bookTour']);
    $r->post('/history/book-tour/add-to-program', [HistoryController::class, 'addTourToProgram']);
    $r->get('/history/route-map', [HistoryController::class, 'routeMap']);
    $r->get('/history/st-bavos-church', [HistoryController::class, 'stBavosChurch']);
    $r->get('/history/molen-de-adriaan', [HistoryController::class, 'molenDeAdriaan']);
    $r->get('/program', [ProgramController::class, 'index']);
    $r->post('/program/remove', [ProgramController::class, 'removeItem']);
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
        $routeArguments = array_map(
            static function ($value) {
                if (is_string($value) && ctype_digit($value)) {
                    return (int) $value;
                }

                return $value;
            },
            array_values($vars)
        );

        call_user_func_array([$controller, $method], $routeArguments);

        break;
}

function createController(string $controllerClass)
{

    $pageRepo = new App\Repositories\AdminPageRepository();
    $pageService = new App\Services\AdminPageService($pageRepo);

    $imageRepo = new App\Repositories\ImageRepository();
    $imageService = new App\Services\ImageService($imageRepo);

    $sectionRepo = new App\Repositories\PageSectionRepository();
    $sectionService = new App\Services\PageSectionService($sectionRepo, $imageService);


    switch ($controllerClass) {

        case App\Controllers\HomeController::class:

            return new App\Controllers\HomeController($sectionService, $pageService);

        case App\Controllers\YummyController::class:

            $resRepo = new App\Repositories\RestaurantRepository();
            $restaurantService = new App\Services\RestaurantService($resRepo);
            $programService = new App\Services\ProgramService();
            $reservationEmailService = new App\Services\ReservationEmailService();
            $userRepo = new App\Repositories\UserRepository();
            $userService = new App\Services\UserService($userRepo);

            return new App\Controllers\YummyController($restaurantService, $pageService, $sectionService, $programService, $reservationEmailService, $userService);


        case App\Controllers\HistoryController::class:

            $pageRepo = new App\Repositories\AdminPageRepository();
            $pageService = new App\Services\AdminPageService($pageRepo);

            $imageRepo = new App\Repositories\ImageRepository();
            $imageService = new App\Services\ImageService($imageRepo);

            $sectionRepo = new App\Repositories\PageSectionRepository();
            $sectionService = new App\Services\PageSectionService($sectionRepo, $imageService);
            $programService = new App\Services\ProgramService();

            return new App\Controllers\HistoryController($sectionService, $pageService, $programService);


        case App\Controllers\AuthController::class:

            $repo = new App\Repositories\UserRepository();
            $service = new App\Services\UserService($repo);

            return new App\Controllers\AuthController($service);


        case App\Controllers\ShopController::class:

            $programService = new App\Services\ProgramService();

            return new App\Controllers\ShopController($programService);


        case App\Controllers\EventController::class:

            $eventCatalogRepository = new App\Repositories\EventCatalogRepository();
            $eventCatalogService = new App\Services\EventCatalogService($eventCatalogRepository);
            $programService = new App\Services\ProgramService();

            return new App\Controllers\EventController($eventCatalogService, $programService);


        case App\Controllers\ProgramController::class:

            $programService = new App\Services\ProgramService();

            return new App\Controllers\ProgramController($programService);


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

        default:
            return new $controllerClass();
    }
}
