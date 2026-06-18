<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\CmsController;
use App\Controllers\EventController;
use App\Controllers\HistoryController;
use App\Controllers\HomeController;
use App\Controllers\ProgramController;
use App\Controllers\ShopController;
use App\Controllers\StoriesController;
use App\Controllers\TicketController;
use App\Controllers\YummyController;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

require __DIR__ . '/../app/bootstrap.php';
require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Models/Enum.php';
require_once __DIR__ . '/../app/config.php';

\App\Support\SessionUser::hydrateFromDatabaseIfNeeded();

$dispatcher = simpleDispatcher(static function (RouteCollector $r): void {
    $r->get('/registerForm', [AuthController::class, 'showRegisterForm']);
    $r->post('/register', [AuthController::class, 'register']);
    $r->get('/loginForm', [AuthController::class, 'showLoginForm']);
    $r->post('/login', [AuthController::class, 'login']);
    $r->get('/logout', [AuthController::class, 'logout']);

    $r->get('/admin/loginForm', [AuthController::class, 'showLoginForm']);
    $r->post('/admin/login', [AuthController::class, 'login']);
    $r->get('/admin/logout', [AuthController::class, 'logout']);
    $r->get('/admin/register', [AuthController::class, 'showRegisterForm']);
    $r->post('/admin/register', [AuthController::class, 'register']);

    $r->get('/admin', [CmsController::class, 'index']);
    $r->get('/admin/dashboard', [CmsController::class, 'index']);
    $r->get('/admin/dashboard/{page_id:\d+}/delete', [CmsController::class, 'deletePage']);
    $r->get('/admin/pages/createPage', [CmsController::class, 'createPageForm']);
    $r->post('/admin/pages/create', [CmsController::class, 'createPage']);
    $r->get('/admin/pages/{page_id:\d+}/editForm', [CmsController::class, 'editPageForm']);
    $r->post('/admin/pages/{page_id:\d+}/edit', [CmsController::class, 'editPage']);
    $r->get('/admin/pages/viewPage', [CmsController::class, 'viewPages']);
    $r->get('/admin/pageSection/{page_id:\d+}/pageSectionForm', [CmsController::class, 'pageSectionForm']);
    $r->post('/admin/pageSection/{page_id:\d+}/createPage', [CmsController::class, 'createPageSection']);
    $r->get('/admin/pageSection/render-fields', [CmsController::class, 'renderSectionForm']);
    $r->get('/admin/pageSection/{page_id:\d+}/editSectionForm', [CmsController::class, 'editSectionForm']);
    $r->post('/admin/pageSection/{section_id:\d+}/editSection', [CmsController::class, 'editSection']);
    $r->get('/admin/pageSection/{page_id:\d+}/viewPageSections', [CmsController::class, 'viewPageSections']);
    $r->get('/admin/pageSection/editPage', [CmsController::class, 'updatePageSection']);
    $r->get('/admin/pageSection/{section_id:\d+}/deleteSection', [CmsController::class, 'deleteSection']);
    $r->get('/admin/users', [CmsController::class, 'manageUsersPage']);
    $r->get('/admin/events/{event_id:\d+}', [CmsController::class, 'viewEventPage']);
    $r->get('/admin/events/{event_id:\d+}/delete', [CmsController::class, 'deleteEventPage']);
    $r->get('/admin/events/{event_id:\d+}/edit', [CmsController::class, 'updateEventPage']);
    $r->post('/admin/media/upload', [CmsController::class, 'uploadImage']);

    $r->get('/', [HomeController::class, 'index']);
    $r->get('/home', [HomeController::class, 'index']);
    $r->get('/events', [EventController::class, 'index']);
    $r->post('/events/add-to-program', [EventController::class, 'addToProgram']);

    $r->post('/checkout/pay', [ShopController::class, 'pay']);
    $r->get('/checkout/success', [ShopController::class, 'checkoutSuccess']);
    $r->get('/checkout/cancel', [ShopController::class, 'checkoutCancel']);
    $r->post('/stripe/webhook', [ShopController::class, 'stripeWebhook']);
    $r->get('/orders/{orderId:\d+}/success', [ShopController::class, 'success']);
    $r->get('/orders/{orderId:\d+}/invoice', [ShopController::class, 'invoice']);

    $r->get('/yummy', [YummyController::class, 'yummy']);
    $r->get('/yummy/ratatouille', [YummyController::class, 'ratatouille']);
    $r->post('/yummy/ratatouille/book-reservation', [YummyController::class, 'bookReservation']);
    $r->get('/yummy/bistro-toujours', [YummyController::class, 'bistroToujours']);
    $r->post('/yummy/bistro-toujours/book-reservation', [YummyController::class, 'bookBistroToujoursReservation']);

    $r->get('/stories', [StoriesController::class, 'index']);
    $r->post('/stories/add-to-program', [StoriesController::class, 'addShowToProgram']);

    $r->get('/history', [HistoryController::class, 'index']);
    $r->get('/history/book-tour', [HistoryController::class, 'bookTour']);
    $r->post('/history/book-tour/add-to-program', [HistoryController::class, 'addTourToProgram']);
    $r->get('/history/route-map', [HistoryController::class, 'routeMap']);
    $r->get('/history/st-bavos-church', [HistoryController::class, 'stBavosChurch']);
    $r->get('/history/molen-de-adriaan', [HistoryController::class, 'molenDeAdriaan']);

    $r->get('/program', [ProgramController::class, 'index']);
    $r->post('/program/remove', [ProgramController::class, 'removeItem']);

    $r->get('/qr/{token:[a-f0-9]{64}}', [TicketController::class, 'qrImage']);
    $r->get('/admin/tickets/scan', [TicketController::class, 'scanPage']);
    $r->post('/admin/tickets/{token:[a-f0-9]{64}}/scan', [TicketController::class, 'scan']);
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
        echo '404 - Page not found';
        break;

    case Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo '405 - Method not allowed';
        break;

    case Dispatcher::FOUND:
        [$controllerClass, $method] = $routeInfo[1];
        $vars = $routeInfo[2];

        if (
            str_starts_with($uri, '/admin')
            && !in_array($uri, $publicAdminRoutes, true)
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

function createImageService(): App\Services\ImageService
{
    return new App\Services\ImageService(new App\Repositories\ImageRepository());
}

function createMailer(): App\Services\IMailer
{
    return new App\Services\Mailer();
}

function createPageService(): App\Services\CmsService
{
    return new App\Services\CmsService(new App\Repositories\CmsRepository());
}

function createSectionService(): App\Services\PageSectionService
{
    return new App\Services\PageSectionService(
        new App\Repositories\PageSectionRepository(),
        createImageService()
    );
}

function createController(string $controllerClass)
{
    switch ($controllerClass) {
        case HomeController::class:
            return new HomeController(createSectionService(), createPageService());

        case YummyController::class:
            return new YummyController(
                createPageService(),
                createSectionService(),
                new App\Services\ProgramService(),
                new App\Services\ReservationEmailService(createMailer()),
                createYummyReservationCatalogService()
            );

        case HistoryController::class:
            return new HistoryController(
                createSectionService(),
                createPageService(),
                new App\Services\ProgramService(),
                createHistoryBookingCatalogService()
            );

        case AuthController::class:
            return new AuthController(new App\Services\UserService(new App\Repositories\UserRepository()));

        case ShopController::class:
            return createShopController();

        case EventController::class:
            return new EventController(
                createEventCatalogService(),
                new App\Services\ProgramService()
            );

        case StoriesController::class:
            return new StoriesController(createStoriesService());

        case ProgramController::class:
            return new ProgramController(
                new App\Services\ProgramService(),
                createOrderService()
            );

        case TicketController::class:
            return new TicketController(
                new App\Services\TicketService(new App\Repositories\TicketRepository())
            );

        case CmsController::class:
            return new CmsController(
                createPageService(),
                createSectionService(),
                new App\Services\UserService(new App\Repositories\UserRepository()),
                createImageService()
            );

        default:
            throw new \RuntimeException('Unknown controller: ' . $controllerClass);
    }
}

function createOrderService(): App\Services\OrderService
{
    return new App\Services\OrderService(new App\Repositories\OrderRepository());
}

function createEventCatalogService(): App\Services\EventCatalogService
{
    return new App\Services\EventCatalogService(new App\Repositories\EventCatalogRepository());
}

function createStoriesService(): App\Services\StoriesService
{
    return new App\Services\StoriesService(
        createPageService(),
        createSectionService(),
        new App\Repositories\StoriesRepository()
    );
}

function createHistoryBookingCatalogService(): App\Services\HistoryBookingCatalogService
{
    return new App\Services\HistoryBookingCatalogService(createPageService(), createSectionService());
}

function createYummyReservationCatalogService(): App\Services\YummyReservationCatalogService
{
    return new App\Services\YummyReservationCatalogService(createPageService(), createSectionService());
}

function createCheckoutValidationService(): App\Services\CheckoutValidationService
{
    return new App\Services\CheckoutValidationService(
        createEventCatalogService(),
        createHistoryBookingCatalogService(),
        createYummyReservationCatalogService()
    );
}

function createShopController(): ShopController
{
    $invoiceService = new App\Services\OrderInvoiceService();

    return new ShopController(
        new App\Services\ProgramService(),
        createOrderService(),
        createCheckoutValidationService(),
        new App\Services\StripePaymentService(),
        new App\Repositories\PendingCheckoutRepository(),
        new App\Services\OrderEmailService(createMailer(), $invoiceService),
        $invoiceService
    );
}
