<?php

define('BASE_PATH', dirname(__DIR__));

require __DIR__ . '/../vendor/autoload.php';

set_exception_handler(function (Throwable $e) {
    if ($e instanceof App\Exceptions\HttpException) {
        (new App\Support\Http\Response($e->getMessage(), $e->getStatusCode()))->send();
        exit;
    }
    echo $e->getMessage();
    exit;
});

$container = App\Support\Container::getInstance();

$container->bind(App\Support\Config::class, function () {
    return new App\Support\Config($_ENV);
});

$container->bind(
    App\Interfaces\Database\IConnectionInterface::class,
    App\Support\Database\Connection::class
);

$container->bind(App\Support\Http\Response::class);

$container->bind(
    App\Support\Http\Request::class,
    (new App\Support\Http\MiddlewareHandler([
        new App\Support\Http\Middleware\SessionStartMiddleware(),
        new App\Support\Http\Middleware\CsrfMiddleware,
        new App\Support\Http\Middleware\TrimStringAndEmptyStringConvertNullMiddleware,
    ]))->handle(App\Support\Http\Request::createFromGlobals())
);

$router = new App\Support\Router;

$router->get('/', [App\Controllers\FormController::class, 'index']);
$router->post('/', [App\Controllers\FormController::class, 'store']);
$router->get('/info', function (){
    phpinfo();
});

$router->fallback(function (App\Support\Http\Request $request, App\Support\Http\Response $response) {
    $response->setStatus(404);
    $response->setContent('Not found');
    return $response;
});

$response = $router->resolve(
    uri: parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),
    method: $_SERVER['REQUEST_METHOD']
);

$response->send();
