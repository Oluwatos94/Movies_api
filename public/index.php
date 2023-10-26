<?php

use DI\Container;
use Dotenv\Dotenv;
use MoviesApi\App\Database;
use MoviesApi\Controllers\ExceptionController;
use MoviesApi\Middlewares\MiddlewareAfter;
use MoviesApi\Middlewares\MiddlewareBefore;
use Slim\Factory\AppFactory;
use Slim\Psr7\Response;
use Slim\Routing\RouteCollectorProxy;

require __DIR__ . '/../vendor/autoload.php';


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


$app->group('/v1', function (RouteCollectorProxy $group) {
$group->get('/movies', 'MoviesApi\Controllers\MoviesController:indexAction');
$group->post('/movies', 'MoviesApi\Controllers\MoviesController:createAction');
$group->put('/movies/{id:[0-9]+}', 'MoviesApi\Controllers\MoviesController:updateAllByIdAction');
$group->delete('/movies/{id:[0-9]+}', 'MoviesApi\Controllers\MoviesController:deleteAction');
$group->patch('/movies/{id:[0-9]+}', 'MoviesApi\Controllers\MoviesController:partialUpdateAction');
$group->get('/movies/{numberPerPage}', 'MoviesApi\Controllers\MoviesController:getListPerPage');
$group->get('/movies/{numberPerPage}/sort/{fieldToSort}', 'MoviesApi\Controllers\MoviesController:getSortedMovies');
$group->get('/apidocs','\MoviesApi\Controllers\OpenApiController:documentationsAction');
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