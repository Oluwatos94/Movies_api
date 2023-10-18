<?php

namespace MoviesApi\Controllers;

use Laminas\Diactoros\Response\JsonResponse;
use MoviesApi\Middlewares\MiddlewareAfter;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class ExceptionController extends A_controller
{
    public function notFound(Response $response, Request $request): JsonResponse
    {
        $middleware = new MiddlewareAfter($this->container);
        $payload = ['status' => 404, 'message' => 'not found'];
        $response = new JsonResponse($payload, 404);
        $middleware->logResponse($response);
        return $response;
    }
}