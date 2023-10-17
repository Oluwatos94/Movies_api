<?php

namespace MoviesApi\Controllers;

use DI\Container;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;
abstract class A_controller
{
    protected Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }
    protected function render(array $data, ResponseInterface $response): ResponseInterface
    {
        $payload = json_encode($data, JSON_PRETTY_PRINT);
        $response->getBody()->write((string)$payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}