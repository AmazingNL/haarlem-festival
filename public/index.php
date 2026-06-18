<?php
declare(strict_types=1);


use App\Controllers\YummyController;
use FastRoute\RouteCollector;
use FastRoute\Dispatcher;
use function FastRoute\simpleDispatcher;

use App\Controllers\AuthController;
use App\Controllers\CmsController;
use App\Controllers\DanceController;
use App\Controllers\EventController;
use App\Controllers\HomeController;
use App\Controllers\HistoryController;
use App\Controllers\StoriesController;
use App\Controllers\PaymentController;
use App\Controllers\ShopController;
use App\Controllers\ProgramController;
use App\Controllers\TicketController;

require __DIR__ . '/../app/bootstrap.php';
require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Models/Enum.php';
require_once __DIR__ . '/../app/Config.php';

\App\Support\SessionUser::hydrateFromDatabaseIfNeeded();

$dispatcher = simpleDispatcher(function (RouteCollector $r) {

    $r->get('/admin/register', [AuthController::class, 'showRegisterForm']);
    $r->post('/admin/register', [AuthController::class, 'register']);

    $r->get('/admin/loginForm', [AuthController::class, 'showLoginForm']);
    $r->post('/admin/login', [AuthController::class, 'login']);

    $r->get('/admin/logout', [AuthController::class, 'logout']);


    $r->get('/admin', [CmsController::class, 'index']);
    $r->get('/admin/dashboard', [CmsController::class, 'index']);

    $r->get('/admin/dashboard/{page_id:\d+}/delete', [CmsController::class, 'deletePage']);


    $r->get('/admin/pages', [CmsController::class, 'viewPages']);
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
    $r->get('/admin/pageSection/{section_id:\d+}/deleteSection', [CmsController::class, 'deleteSection']);


    $r->get('/admin/users', [CmsController::class, 'manageUsersPage']);
    $r->get('/admin/users/create', [CmsController::class, 'createUserForm']);
    $r->post('/admin/users/create', [CmsController::class, 'createUser']);
    $r->get('/admin/users/{user_id:\d+}/edit', [CmsController::class, 'editUserForm']);
    $r->post('/admin/users/{user_id:\d+}/edit', [CmsController::class, 'editUser']);
    $r->get('/admin/users/{user_id:\d+}/delete', [CmsController::class, 'deleteUser']);
    $r->get('/admin/orders/export', [CmsController::class, 'exportOrders']);
    $r->get('/admin/orders', [CmsController::class, 'viewOrders']);
    $r->get('/admin/orders/{order_id:\d+}', [CmsController::class, 'viewOrderDetail']);
    $r->get('/admin/seats', [CmsController::class, 'viewSeatsOverview']);
    $r->get('/admin/dance/seats', [CmsController::class, 'viewDanceSeatOverview']);
    $r->get('/admin/dance/seats/{event_id:\d+}', [CmsController::class, 'viewDanceEventSeats']);
    $r->post('/admin/dance/seats/{event_id:\d+}', [CmsController::class, 'updateDanceEventSeats']);


    $r->post('/admin/media/upload', [CmsController::class, 'uploadImage']);
    $r->get('/admin/tickets/scan', [TicketController::class, 'scanPage']);
    $r->post('/admin/tickets/{token:[a-f0-9]{64}}/scan', [TicketController::class, 'scan']);


    $r->get('/registerForm', [AuthController::class, 'showRegisterForm']);
    $r->post('/register', [AuthController::class, 'register']);

    $r->get('/loginForm', [AuthController::class, 'showLoginForm']);
    $r->post('/login', [AuthController::class, 'login']);

    $r->get('/logout', [AuthController::class, 'logout']);
    $r->get('/employee/dashboard', [TicketController::class, 'scanPage']);
    $r->get('/employee/tickets/scan', [TicketController::class, 'scanPage']);
    $r->post('/employee/tickets/{token:[a-f0-9]{64}}/scan', [TicketController::class, 'scan']);


    $r->get('/', [HomeController::class, 'index']);
    $r->get('/home', [HomeController::class, 'index']);
    $r->get('/qr/{token:[a-f0-9]{64}}', [TicketController::class, 'qrImage']);
    $r->get('/dance', [DanceController::class, 'index']);
    $r->get('/dance/artists/{slug:[a-z0-9-]+}', [DanceController::class, 'artistDetail']);
    $r->get('/events', [EventController::class, 'index']);
    $r->post('/events/add-to-program', [EventController::class, 'addToProgram']);
    $r->get('/checkout', [ShopController::class, 'checkout']);
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
    // Payments
    $r->get('/payments/checkout/{order_id:\d+}', [PaymentController::class, 'checkoutPage']);
    $r->post('/payments/create-session', [PaymentController::class, 'createCheckoutSession']);
    $r->post('/webhook/stripe', [PaymentController::class, 'webhook']);
    $r->get('/stories/{slug}', [StoriesController::class, 'detail']);
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

        if (str_starts_with($uri, '/employee')) {
            if (empty($_SESSION['user_id'])) {
                header('Location: /loginForm');
                exit;
            }

            $role = strtolower((string) ($_SESSION['user_role'] ?? ''));
            if (!in_array($role, ['employee', 'admin'], true)) {
                http_response_code(403);
                echo '403 - Forbidden';
                exit;
            }
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

function createImageService(): App\Services\Implementations\ImageService
{
    return new App\Services\Implementations\ImageService(new App\Repositories\ImageRepository());
}

function createMailer(): App\Services\Interfaces\IMailer
{
    return new App\Services\Implementations\Mailer();
}

function createAccountEmailService(): App\Services\Interfaces\IAccountEmailService
{
    return new App\Services\Implementations\AccountEmailService(createMailer());
}

function createPageService(): App\Services\Implementations\CmsService
{
    return new App\Services\Implementations\CmsService(new App\Repositories\CmsRepository());
}

function createSectionService(): App\Services\Implementations\PageSectionService
{
    return new App\Services\Implementations\PageSectionService(
        new App\Repositories\PageSectionRepository(),
        createImageService()
    );
}

function createDanceArtistService(): App\Services\Implementations\DanceArtistService
{
    return new App\Services\Implementations\DanceArtistService(new App\Repositories\DanceArtistRepository());
}

function createDanceScheduleService(): App\Services\Implementations\DanceScheduleService
{
    return new App\Services\Implementations\DanceScheduleService(new App\Repositories\DanceScheduleRepository());
}

function createController(string $controllerClass)
{

    $pageRepo = new App\Repositories\CmsRepository();
    $pageService = new App\Services\Implementations\CmsService($pageRepo);

    $imageRepo = new App\Repositories\ImageRepository();
    $imageService = new App\Services\Implementations\ImageService($imageRepo);

    $sectionRepo = new App\Repositories\PageSectionRepository();
    $sectionService = new App\Services\Implementations\PageSectionService($sectionRepo, $imageService);


    switch ($controllerClass) {

        case App\Controllers\HomeController::class:

            return new App\Controllers\HomeController(createSectionService(), createPageService());

        case App\Controllers\YummyController::class:

            return new App\Controllers\YummyController(
                createPageService(),
                createSectionService(),
                new App\Services\Implementations\ProgramService(),
                new App\Services\Implementations\ReservationEmailService(createMailer()),
                createRestaurantBookingService(),
                createRestaurantAvailabilityService()
            );


        case App\Controllers\HistoryController::class:

            $pageRepo = new App\Repositories\CmsRepository();
            $pageService = new App\Services\Implementations\CmsService($pageRepo);

            $imageRepo = new App\Repositories\ImageRepository();
            $imageService = new App\Services\Implementations\ImageService($imageRepo);

            $sectionRepo = new App\Repositories\PageSectionRepository();
            $sectionService = new App\Services\Implementations\PageSectionService($sectionRepo, $imageService);
            $programService = new App\Services\Implementations\ProgramService();

            return new App\Controllers\HistoryController($sectionService, $pageService, $programService, createHistoryBookingService());


        case App\Controllers\AuthController::class:

            $repo = new App\Repositories\UserRepository();
            $service = new App\Services\Implementations\UserService($repo);

            return new App\Controllers\AuthController($service);


        case App\Controllers\ShopController::class:

            return createShopController();


        case App\Controllers\EventController::class:

            return new App\Controllers\EventController(
                createEventCatalogService(),
                createEventBookingService(),
                new App\Services\Implementations\ProgramService()
            );

        case App\Controllers\DanceController::class:

            return new App\Controllers\DanceController(
                createPageService(),
                createSectionService(),
                createDanceArtistService(),
                createDanceScheduleService()
            );

        case App\Controllers\StoriesController::class:

            return new App\Controllers\StoriesController(createStoriesService());


        case App\Controllers\ProgramController::class:

            return new App\Controllers\ProgramController(
                new App\Services\Implementations\ProgramService(),
                createOrderService()
            );

        case App\Controllers\TicketController::class:

            return new App\Controllers\TicketController(
                new App\Services\Implementations\TicketService(new App\Repositories\TicketRepository())
            );


        case App\Controllers\CmsController::class:

            $pageRepo = new App\Repositories\CmsRepository();
            $pageService = new App\Services\Implementations\CmsService($pageRepo);

            $userRepo = new App\Repositories\UserRepository();
            $userService = new App\Services\Implementations\UserService($userRepo);

            $imageRepo = new App\Repositories\ImageRepository();
            $imageService = new App\Services\Implementations\ImageService($imageRepo);

            $sectionRepo = new App\Repositories\PageSectionRepository();
            $sectionService = new App\Services\Implementations\PageSectionService($sectionRepo, $imageService);

            return new App\Controllers\CmsController(
                $pageService,
                $sectionService,
                $userService,
                createImageService(),
                createOrderService(),
                createAdminDanceAvailabilityService()
            );

        case App\Controllers\StoriesController::class:

            return new App\Controllers\StoriesController(createStoriesService());


        default:
            return new $controllerClass();
    }
}

function createStoriesService(): App\Services\Implementations\StoriesService
{
    return new App\Services\Implementations\StoriesService(
        createPageService(),
        createSectionService(),
        new App\Repositories\StoriesRepository()
    );
}

function createOrderService(): App\Services\Implementations\OrderService
{
    return new App\Services\Implementations\OrderService(new App\Repositories\OrderRepository());
}

function createAdminDanceAvailabilityService(): App\Services\Implementations\AdminDanceAvailabilityService
{
    return new App\Services\Implementations\AdminDanceAvailabilityService(
        new App\Repositories\AdminDanceAvailabilityRepository()
    );
}

function createEventCatalogService(): App\Services\Implementations\Catalog\EventCatalogService
{
    return new App\Services\Implementations\Catalog\EventCatalogService(new App\Repositories\EventCatalogRepository());
}

function createEventBookingService(): App\Services\Implementations\Booking\EventBookingService
{
    return new App\Services\Implementations\Booking\EventBookingService(new App\Repositories\EventCatalogRepository());
}

function createHistoryBookingService(): App\Services\Implementations\Booking\HistoryBookingService
{
    return new App\Services\Implementations\Booking\HistoryBookingService(createPageService(), createSectionService());
}

function createRestaurantBookingService(): App\Services\Implementations\Booking\RestaurantBookingService
{
    return new App\Services\Implementations\Booking\RestaurantBookingService(createPageService(), createSectionService(), createReservationService());
}

function createReservationService(): App\Services\Implementations\Booking\ReservationService
{
    return new App\Services\Implementations\Booking\ReservationService(
        new App\Repositories\RestaurantRepository(),
        new App\Repositories\ReservationRepository()
    );
}

function createRestaurantAvailabilityService(): App\Services\Implementations\Booking\RestaurantAvailabilityService
{
    return new App\Services\Implementations\Booking\RestaurantAvailabilityService(
        new App\Repositories\RestaurantRepository(),
        new App\Repositories\ReservationRepository()
    );
}

function createCheckoutValidationService(): App\Services\Implementations\Booking\CheckoutValidationService
{
    return new App\Services\Implementations\Booking\CheckoutValidationService(
        [
            createEventBookingService(),
            createHistoryBookingService(),
            createRestaurantBookingService(),
        ]
    );
}

function createShopController(): App\Controllers\ShopController
{
    return new App\Controllers\ShopController(
        new App\Services\Implementations\ProgramService(),
        createOrderService(),
        createCheckoutValidationService(),
        new App\Services\Implementations\StripePaymentService(),
        new App\Repositories\PendingCheckoutRepository(),
        new App\Services\Implementations\OrderEmailService(createMailer(), new App\Services\Implementations\InvoiceService()),
        new App\Services\Implementations\InvoiceService()
    );
}
