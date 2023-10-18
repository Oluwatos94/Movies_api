<?php

namespace MoviesApi\Controllers;

use DI\DependencyException;
use DI\NotFoundException;
use PDO;
use DI\Container;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;
use slim\views\PhpRenderer;
abstract class A_controller
{
    protected Container $container;

    protected mixed $pdo;
    protected mixed $view;

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function __construct(Container $container)
    {
        $this->pdo = $container->get('database');
        $this->view = $container->get('view');
        $this->container = $container;
    }
    protected function render(array $data, ResponseInterface $response): ResponseInterface
    {
        $payload = json_encode($data, JSON_PRETTY_PRINT);
        $response->getBody()->write((string)$payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}