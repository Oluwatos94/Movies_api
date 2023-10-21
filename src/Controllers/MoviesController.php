<?php

namespace MoviesApi\Controllers;

use Cassandra\Exception\AuthenticationException;
use Laminas\Diactoros\Response\JsonResponse;
use DI\DependencyException;
use DI\NotFoundException;
use Fig\Http\Message\StatusCodeInterface;
use MoviesApi\models\Movies;
use slim\psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;


class MoviesController extends A_controller
{
    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function indexAction(request $request, response $response): ResponseInterface
    {
        $movies = new movies($this->container);
        $data = $movies->findAll();
        return $this->render($data, $response);
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function createAction(request $request, response $response): ResponseInterface
    {
        $requestBody = $request->getParsedBody();

        $title = filter_var($requestBody['title'], FILTER_SANITIZE_SPECIAL_CHARS);
        $year = filter_var($requestBody['year'], FILTER_VALIDATE_INT);
        $released = filter_var($requestBody['released'], FILTER_SANITIZE_SPECIAL_CHARS);
        $runtime = filter_var($requestBody['runtime'], FILTER_SANITIZE_SPECIAL_CHARS);
        $genre = filter_var($requestBody['genre'], FILTER_SANITIZE_SPECIAL_CHARS);
        $director = filter_var($requestBody['director'], FILTER_SANITIZE_SPECIAL_CHARS);
        $actors = filter_var($requestBody['actors'],FILTER_SANITIZE_SPECIAL_CHARS);
        $country = filter_var($requestBody['country'], FILTER_SANITIZE_SPECIAL_CHARS);
        $poster = filter_var($requestBody['poster'], FILTER_SANITIZE_SPECIAL_CHARS);
        $imdb = filter_var($requestBody['imdb'],FILTER_SANITIZE_NUMBER_FLOAT);
        $type = filter_var($requestBody['type'],FILTER_SANITIZE_SPECIAL_CHARS);

        $movies = new Movies($this->container);
            $movies->insert([$title, $year, $runtime, $director, $released, $actors, $country, $poster, $imdb, $type, $genre]);
        $responseData = [
            'code' => StatusCodeInterface::STATUS_OK,
            'message' => 'Movies has been created.'
        ];
        return $this->render($responseData, $response);
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function updateAction(request $request, response $response, $args = []): ResponseInterface
    {
        $requestBody = $this->getRequestBodyAsArray($request);

        $id = $args['id'];
        $title = filter_var($requestBody['title'], FILTER_SANITIZE_SPECIAL_CHARS);
        $year = filter_var($requestBody['year'], FILTER_VALIDATE_INT);
        $released = filter_var($requestBody['released'], FILTER_SANITIZE_SPECIAL_CHARS);
        $runtime = filter_var($requestBody['runtime'], FILTER_SANITIZE_SPECIAL_CHARS);
        $genre = filter_var($requestBody['genre'], FILTER_SANITIZE_SPECIAL_CHARS);
        $director = filter_var($requestBody['director'], FILTER_SANITIZE_SPECIAL_CHARS);
        $actors = filter_var($requestBody['actors'],FILTER_SANITIZE_SPECIAL_CHARS);
        $country = filter_var($requestBody['country'], FILTER_SANITIZE_SPECIAL_CHARS);
        $poster = filter_var($requestBody['poster'], FILTER_SANITIZE_SPECIAL_CHARS);
        $imdb = filter_var($requestBody['imdb'],FILTER_SANITIZE_NUMBER_FLOAT);
        $type = filter_var($requestBody['type'],FILTER_SANITIZE_SPECIAL_CHARS);

        $movies = new Movies($this->container);
        $movies->updateAction([$id, $title, $year, $runtime, $director, $released, $actors, $country, $poster, $imdb, $type, $genre]);
        $responseData = [
            'code' => StatusCodeInterface::STATUS_OK,
            'message' => 'Movies successfully updated.'
        ];
        return $this->render($responseData, $response);
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function deleteAction(request $request, response $response, $args = []): ResponseInterface
    {
        $id = $args['id'];
        $movies = new Movies($this->container);
        $movies->delete($id);
        $responseData = [
            'code' => StatusCodeInterface::STATUS_OK,
            'message' => 'Movies has been deleted successfully.'
        ];
        return $this->render($responseData, $response);
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