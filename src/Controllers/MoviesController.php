<?php

namespace MoviesApi\Controllers;

use DI\DependencyException;
use DI\NotFoundException;
use Fig\Http\Message\StatusCodeInterface;
use MoviesApi\models\Movies;
use slim\psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;


class MoviesController extends A_controller
{
    public function indexAction(request $request, response $response): ResponseInterface
    {
        $movies = new movies($this->container);
        $data = $movies->findAll();
        return $this->render($data, $response);
    }

    public function createAction(request $request, response $response): ResponseInterface
    {
        $data = ['0' => ['title' => 'created']];
        return $this->render($data, $response);
    }

    public function updateAction(request $request, response $response, $args = []): ResponseInterface
    {
        $id = $args['id'];
        $data = ['0' => ['id' => $id, 'action' => 'update']];
        return $this->render($data, $response);
    }

    public function deleteAction(request $request, response $response, $args = []): ResponseInterface
    {
        $id = $args['id'];
        $data = ['0' => ['id' => $id, 'action' => 'delete']];
        return $this->render($data, $response);
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function fakeAction(Request $request, Response $response, $args = []): ResponseInterface
    {
        $movies = new Movies($this->container);
        $movies->fakeDataInput($this->container);

        $dataResponse = [
            'code' => StatusCodeInterface::STATUS_OK,
            'message' => 'Data has been inserted'
        ];
        return $this->render($dataResponse, $response);
    }
}