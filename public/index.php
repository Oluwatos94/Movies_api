<?php

use MoviesApi\App\Database;
use MoviesApi\Controllers\ExceptionController;
use MoviesApi\Middlewares\MiddlewareAfter;
use MoviesApi\Middlewares\MiddlewareBefore;
use DI\Container;
use Dotenv\Dotenv;
use Slim\Factory\AppFactory;
use Slim\Psr7\Response;
use Slim\Routing\RouteCollectorProxy;
use Slim\Views\PhpRenderer;

require __DIR__ . '/../vendor/autoload.php';

//$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
//$dotenv->safeLoad();

$container = new Container();
AppFactory::setContainer($container);

$app = AppFactory::create();

$container->set('settings', function () {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->safeLoad();
    return $_ENV;
});

$container->set('database', function () use ($container) {
$db = new Database($container);
    return $db->conn;
});

//$container->set('view', function () {
//    return new PhpRenderer(__DIR__ . "/../src/Views");
//});

$app->group('/v1', function (RouteCollectorProxy $group) {
$group->get('/movies', 'MoviesApi\Controllers\MoviesController:indexAction');
$group->post('/movies', 'MoviesApi\Controllers\MoviesController:createAction');
$group->put('/movies/{id:[0-9]+}', 'MoviesApi\Controllers\MoviesController:updateAction');
$group->delete('/movies/{id:[0-9]+}', 'MoviesApi\Controllers\MoviesController:deleteAction');
$group->get('/movies/fill-with-fake-data','\MoviesApi\Controllers\MoviesController:fakeAction');
})->add(new MiddlewareBefore($container))->add(new MiddlewareAfter($container));

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$errorMiddleware->setErrorHandler(
    Slim\Exception\HttpNotFoundException::class,
    function (Psr\Http\Message\ServerRequestInterface $request) use ($container)
    {
        $controller = new ExceptionController($container);
        return $controller->notFound($request, new Response());
    });

$app->run();