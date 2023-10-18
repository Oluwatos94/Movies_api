<?php

namespace MoviesApi\models;

use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use Exception;
use Faker\Factory;
use Faker\Generator;
use MoviesApi\App\Database;
use PDO;
class Movies
{
    protected ?PDO $pdo;

    private Generator $faker;

    const DB_TABLE_NAME = 'movies';

    protected int $id; protected string $title;
    protected int $year;  protected string $runtime;
    protected string $director; protected string $released;
    protected string $actorsText; protected string $country;
    protected string $poster;  protected string $type;
    protected float $imdb; protected string $genre;

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function __construct(Container $container)
    {
        $this->pdo = $container->get('database');
    }

    public function findById(): int
    {

    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        $sql = "SELECT * FROM " . self::DB_TABLE_NAME;
        $stm = $this->getPdo()->prepare($sql);
        $stm->execute();
        return $stm->fetchAll();
    }

    /**
     * @param array $data
     * @return int
     */
    public function insert(array $data): int
    {
        $sql = "INSERT INTO " . self::DB_TABLE_NAME .  "(title, year, runtime, director, released, actorsText, 
        country, poster, imdb, type, genre) VALUE(?,?,?,?,?,?,?,?,?,?,?)";
        $stm = $this->getPdo()->prepare($sql);
        $stm->execute([$data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7], $data[8], $data[9], $data[10], $data[11]],);
        return $this->getPdo()->lastInsertId();
    }

    public function update(): bool
    {

    }
    public function delete(): bool
    {

    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    public function fakeDataInput(Container $container): bool
    {
        $this->faker = Factory::create();

        try {
            for ($i = 0; $i < 40; $i++) {
                $this->insert(
                    [
                        $this->faker->numberBetween(1000, 5000), // moviesId input
                        $this->faker->text(200), // title input
                        $this->faker->year('now'), // year input
                        $this->faker->date(), // released
                        $this->faker->numberBetween(60, 240), // runtime
                        $this->faker->word(), //genre
                        $this->faker->name(), // director
                        $this->faker->name(), // actors
                        $this->faker->country(), // country
                        $this->faker->imageUrl(), // poster
                        $this->faker->randomFloat(1, 1, 10), // imdb
                        $this->faker->randomElement(['movie', 'series', 'comedy', 'romance']), // type
                ]);
            }

        } catch (Exception $exception){
            error_log($exception->getMessage());
            return false;
        }
        return true;
    }
}