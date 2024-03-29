<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\{Database, Query, Repository};

class Manager
{
    protected Database $database;
    protected array $repository;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getRepository(string $class): Repository
    {
        $this->repository[$class] ??= new $class($this->database, $this);

        return $this->repository[$class];
    }

    public function createQuery(string $query): Query
    {
        return new Query($query);
    }
}
