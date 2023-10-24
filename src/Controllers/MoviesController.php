<?php

namespace MoviesApi\Controllers;

use Assert\AssertionFailedException;
use DI\DependencyException;
use DI\NotFoundException;
use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\JsonResponse;
use MoviesApi\models\Movies;
use MoviesApi\models\Poster;
use MoviesApi\models\Title;
use slim\psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use OpenApi\Annotations as OA;


/**
 *
 * @OA\Info(
 *     title="Movie API",
 *     version="1.0.0",
 *     @OA\Contact(
 *       email="akinbowat@gmail.com"
 *     )
 *   )
 */
class MoviesController extends A_controller
{
    /**
     *
     * @OA\Get(
     *      path="/v1/movies",
     *      description="Returns all movies",
     * @OA\Response(
     *         response=200,
     *         description="Successful",
     *          ),
     * @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *       ),
     *    )
     * )
     * /
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
     * @OA\Post(
     *      path="/v1/movies",
     *      description="Creates a new movie",
     *      @OA\RequestBody(
     *          description="Movie has been successfully created",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="title",
     *                      description="Title of the new movie",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="year",
     *                      description="Year of the movie",
     *                      type="integer",
     *                  ),
     *                  @OA\Property(
     *                      property="released",
     *                      description="Date of movie released",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="runtime",
     *                      description="Movie runtime",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="genre",
     *                      description="Movie genre",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="director",
     *                      description="Name of the movie director",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="actors",
     *                      description="Name of the movie actor",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="country",
     *                      description="Country where the movie is produced",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="poster",
     *                      description="The image of the movie",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="imdb",
     *                      description="Movie IMDb",
     *                      type="number",
     *                  ),
     *                  @OA\Property(
     *                      property="type",
     *                      description="The type of the movie",
     *                      type="string",
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Movie has been successfully created",
     *      ),
     *      @OA\Response(
     *          response="400",
     *          description="Unexpected error",
     *      ),
     *      @OA\Response(
     *          response="500",
     *          description="Internal server error",
     *      ),
     * )
     *
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
        try{
            $movies->insert([new Title($title), $year, $runtime, $director, $released, $actors, $country, new Poster($poster), $imdb, $type, $genre]);
        } catch (AssertionFailedException $e){
            $responseData = [
                'code' => StatusCodeInterface::STATUS_BAD_REQUEST,
                'message' => $e->getMessage()
            ];
            $response = new JsonResponse($responseData, StatusCodeInterface::STATUS_BAD_REQUEST);
            return $this->render($responseData, $response);
        }
        $responseData = [
            'code' => StatusCodeInterface::STATUS_OK,
            'message' => 'Movies has been created.'
        ];
        return $this->render($responseData, $response);
    }

    /**
     * @OA\Put(
     *      path="/v1/movies/{id}",
     *      description="update a single movie from movies based on movie ID",
     *      @OA\Parameter(
     *           description="ID of movie to be updated",
     *           in="path",
     *           name="id",
     *           required=true,
     *           @OA\Schema(
     *               format="int64",
     *               type="integer"
     *           )
     *       ),
     *           @OA\RequestBody(
     *           description="Movie has been successfully updated",
     *           @OA\MediaType(
     *               mediaType="multipart/form-data",
     *               @OA\Schema(
     *                   type="object",
     *                   @OA\Property(
     *                       property="title",
     *                       description="Title of the new movie",
     *                       type="string",
     *                   ),
     *                   @OA\Property(
     *                       property="year",
     *                       description="Year of the movie",
     *                       type="integer",
     *                   ),
     *                   @OA\Property(
     *                       property="released",
     *                       description="Date of movie released",
     *                       type="string",
     *                   ),
     *                   @OA\Property(
     *                       property="runtime",
     *                       description="Movie runtime",
     *                       type="string",
     *                   ),
     *                   @OA\Property(
     *                       property="genre",
     *                       description="Movie genre",
     *                       type="string",
     *                   ),
     *                   @OA\Property(
     *                       property="director",
     *                       description="Name of the movie director",
     *                       type="string",
     *                   ),
     *                   @OA\Property(
     *                       property="actors",
     *                       description="Name of the movie actor",
     *                       type="string",
     *                   ),
     *                   @OA\Property(
     *                       property="country",
     *                       description="Country where the movie is produced",
     *                       type="string",
     *                   ),
     *                   @OA\Property(
     *                       property="poster",
     *                       description="The image of the movie",
     *                       type="string",
     *                   ),
     *                   @OA\Property(
     *                       property="imdb",
     *                       description="Movie IMDb",
     *                       type="number",
     *                   ),
     *                   @OA\Property(
     *                       property="type",
     *                       description="The type of the movie",
     *                       type="string",
     *                   ),
     *               )
     *           )
     *       ),
     *       @OA\Response(
     *           response=200,
     *           description="Movie has been successfully updated",
     *       ),
     *       @OA\Response(
     *           response="400",
     *           description="Unexpected error",
     *       ),
     *     @OA\Response(
     *            response=404,
     *            description="movie not found",
     *        ),
     *       @OA\Response(
     *           response="500",
     *           description="Internal server error",
     *       ),
     *  )
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function updateAllByIdAction(request $request, response $response, $args = []): ResponseInterface
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
        try {
            $movies->updateAllById([new Title($title), $year, $released, $runtime, $genre, $director, $actors, $country, new poster($poster), $imdb, $type,  $id]);
        } catch (AssertionFailedException $e) {
            $responseData = [
                'code' => StatusCodeInterface::STATUS_BAD_REQUEST,
                'message' => $e->getMessage()
            ];
            $response = new JsonResponse($responseData, StatusCodeInterface::STATUS_BAD_REQUEST);
            return $this->render($responseData, $response);
        }
        $responseData = [
            'code' => StatusCodeInterface::STATUS_OK,
            'message' => 'Movies successfully updated.'
        ];
        return $this->render($responseData, $response);
    }


    /**
     *
     * @OA\Delete(
     *      path="/v1/movies/{id}",
     *      description="deletes a single movie from movies based on movie ID",
     *      @OA\Parameter(
     *          description="ID of movie to delete",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(
     *              format="int64",
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Movie has been deleted"
     *      ),
     *  @OA\Response(
     *             response=400,
     *             description="bad request",
     *         ),
     *  @OA\Response(
     *                  response=404,
     *              description="Movie does not exist",
     *          ),
     *  @OA\Response(
     *              response=500,
     *              description="Internal server error",
     *          ),
     *    )
     * /
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
     * @OA\Patch(
     *       path="/v1/movies/{id}",
     *       description="update specific detail of a movie using its unique ID",
     *       @OA\Parameter(
     *            description="ID of movie to update",
     *            in="path",
     *            name="id",
     *            required=true,
     *            @OA\Schema(
     *                format="int64",
     *                type="integer"
     *            )
     *        ),
     *            @OA\RequestBody(
     *            description="The data to update the movie resource.",
     *            @OA\MediaType(
     *                mediaType="multipart/form-data",
     *                @OA\Schema(
     *                    type="object",
     *                    @OA\Property(
     *                        property="title",
     *                        description="The title of the movie",
     *                        type="string",
     *                    ),
     *                    @OA\Property(
     *                        property="year",
     *                        description="The Year of the movie",
     *                        type="integer",
     *                    ),
     *                    @OA\Property(
     *                        property="released",
     *                        description="The date the movie was released",
     *                        type="string",
     *                    ),
     *                    @OA\Property(
     *                        property="runtime",
     *                        description="The movie runtime",
     *                        type="string",
     *                    ),
     *                    @OA\Property(
     *                        property="genre",
     *                        description="The movie genre",
     *                        type="string",
     *                    ),
     *                    @OA\Property(
     *                        property="director",
     *                        description="The name of the movie director",
     *                        type="string",
     *                    ),
     *                    @OA\Property(
     *                        property="actors",
     *                        description="The name the movie actors",
     *                        type="string",
     *                    ),
     *                    @OA\Property(
     *                        property="country",
     *                        description="The country where the movie was produced",
     *                        type="string",
     *                    ),
     *                    @OA\Property(
     *                        property="poster",
     *                        description="The url of the movie",
     *                        type="string",
     *                    ),
     *                    @OA\Property(
     *                        property="imdb",
     *                        description="The imdb rating of the Movie",
     *                        type="number",
     *                    ),
     *                    @OA\Property(
     *                        property="type",
     *                        description="The type of the movie",
     *                        type="string",
     *                    ),
     *                )
     *            )
     *        ),
     *        @OA\Response(
     *            response=200,
     *            description="Movie has been successfully updated",
     *        ),
     *        @OA\Response(
     *            response="400",
     *            description="Unexpected error",
     *        ),
     *      @OA\Response(
     *             response=404,
     *             description="movie not found",
     *         ),
     *        @OA\Response(
     *            response="500",
     *            description="Internal server error",
     *        ),
     *   )
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function partialUpdateAction(request $request, response $response, $args = []): ResponseInterface
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
        try {
            $movies->partialUpdate([new Title($title), $year, $released, $runtime, $genre, $director, $actors, $country, new poster($poster), $imdb, $type,  $id]);
        } catch (AssertionFailedException $e) {
            $responseData = [
                'code' => StatusCodeInterface::STATUS_BAD_REQUEST,
                'message' => $e->getMessage()
            ];
            $response = new JsonResponse($responseData, StatusCodeInterface::STATUS_BAD_REQUEST);
            return $this->render($responseData, $response);
        }
        $responseData = [
            'code' => StatusCodeInterface::STATUS_OK,
            'message' => 'Updated successfully.'
        ];
        return $this->render($responseData, $response);
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function getListPerPage(Request $request, Response $response, $args = []): ResponseInterface
    {
        $numberPerPage = (int) $args['numberPerPage'];
        $movies = new movies($this->container);
        $data = $movies->getNumberPerPage($numberPerPage);
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