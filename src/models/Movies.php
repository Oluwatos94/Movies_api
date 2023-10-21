<?php

namespace MoviesApi\models;

use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use Exception;
use Faker\Factory;
use PDO;

class Movies
{
    protected ?PDO $pdo;

    const DB_TABLE_NAME = 'movies';

    protected int $id; protected string $title;
    protected int $year;  protected string $runtime;
    protected string $director; protected string $released;
    protected string $actors; protected string $country;
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
        $sql = "INSERT INTO " . self::DB_TABLE_NAME .  "(title, year, runtime, director, released, actors, 
        country, poster, imdb, type, genre) VALUE(?,?,?,?,?,?,?,?,?,?,?)";
        $stm = $this->getPdo()->prepare($sql);
        $stm->execute([$data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7], $data[8], $data[9], $data[10]],);
        return $this->getPdo()->lastInsertId();
    }

    public function updateAction(array $data): void
    {
        $sql = "UPDATE " . self::DB_TABLE_NAME . " SET title=?, year=?, runtime=?, director=?, released=?, actors=?,
         country=?, poster=?, imdb=?, type=?, genre=? WHERE id=?";
        $stm = $this->getPdo()->prepare($sql);
        $stm->execute([$data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7], $data[8], $data[9], $data[10], $data[11]],);
    }
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM " . self::DB_TABLE_NAME . " WHERE id=?";
        try{
            $stm = $this->getPdo()->prepare($sql);
            $stm->execute([$id]);
        } catch (\PDOException $exception) {
            return false;
        }
        return true;
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    public function fakeDataInput(Container $container): bool
    {
        $faker = Factory::create('en_US');
            for ($i = 0; $i < 40; $i++) {
                $this->insert(
                    [
                        $faker->numberBetween(0, 200), // moviesId input
                        $faker->text(50), // title input
                        $faker->year('-10 years'), // year input
                        $faker->numberBetween(60, 240), // runtime
                        $faker->name($gender = null), // director
                        $faker->date(), // released
                        $faker->name($sex = null), // actors
                        $faker->country(), // country
                        $faker->imageUrl(360, 360, 'movies', true, 'action'), // poster
                        $faker->randomFloat(1, 1, 10), // imdb
                        $faker->randomElement(['movie', 'series', 'comedy', 'romance']), // type
                        $faker->word(), //genre
                ]);
            }
        return true;
    }
}