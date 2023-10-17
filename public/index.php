<?php

use DI\Container;
use MoviesApi\App\Database;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

require __DIR__ . '/../vendor/autoload.php';

$container = new Container();
AppFactory::setContainer($container);

$app = AppFactory::create();

//$container->set('settings', function () {
//    $dotenv = Dotenv::createImmutable(__DIR__ . "/..");
//    $dotenv->safeLoad();
//    return $_ENV;
//});

$container->set('database', function(){
$db = new Database();
    return $db->conn;
});

$app->get('/v1/movies', 'MoviesApi\Controllers\moviesController:indexAction');
$app->post('/v1/movies', 'MoviesApi\Controllers\moviesController:createAction');
$app->put('/v1/movies{id}', 'MoviesApi\Controllers\moviesController:updateAction');
$app->delete('/v1/movies{id}', 'MoviesApi\Controllers\moviesController:deleteAction');

$app->run();