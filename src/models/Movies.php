<?php

namespace MoviesApi\models;

use DI\Container;
use MoviesApi\App\Database;
use PDO;
class Movies
{
    protected ?PDO $pdo;

    const DB_TABLE_NAME = 'movies';

    protected int $id; protected string $title;
    protected int $year;  protected string $runtime;
    protected string $director; protected string $released;
    protected string $actorsText; protected string $country;
    protected string $poster;  protected string $type;
    protected float $imdb; protected string $genre;

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

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return void
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getActorsText(): string
    {
        return $this->actorsText;
    }

    /**
     * @param string $actorsText
     * @return void
     */
    public function setActorsText(string $actorsText): void
    {
        $this->actorsText = $actorsText;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getDirector(): string
    {
        return $this->director;
    }

    /**
     * @param string $director
     */
    public function setDirector(string $director): void
    {
        $this->director = $director;
    }

    /**
     * @return string
     */
    public function getGenre(): string
    {
        return $this->genre;
    }

    /**
     * @param string $genre
     */
    public function setGenre(string $genre): void
    {
        $this->genre = $genre;
    }

    /**
     * @return float
     */
    public function getImdb(): float
    {
        return $this->imdb;
    }

    /**
     * @param float $imdb
     */
    public function setImdb(float $imdb): void
    {
        $this->imdb = $imdb;
    }

    /**
     * @return string
     */
    public function getPoster(): string
    {
        return $this->poster;
    }

    /**
     * @param string $poster
     */
    public function setPoster(string $poster): void
    {
        $this->poster = $poster;
    }

    /**
     * @return string
     */
    public function getReleased(): string
    {
        return $this->released;
    }

    /**
     * @param string $released
     */
    public function setReleased(string $released): void
    {
        $this->released = $released;
    }

    /**
     * @return string
     */
    public function getRuntime(): string
    {
        return $this->runtime;
    }

    /**
     * @param string $runtime
     */
    public function setRuntime(string $runtime): void
    {
        $this->runtime = $runtime;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getYear(): int
    {
        return $this->year;
    }

    /**
     * @param int $year
     */
    public function setYear(int $year): void
    {
        $this->year = $year;
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }
}