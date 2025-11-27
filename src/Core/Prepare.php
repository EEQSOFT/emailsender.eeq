<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\Database;

class Prepare
{
    protected Database $database;
    protected string $query;
    public array $params;
    public $stmt;

    public function __construct(Database $database, string $query)
    {
        $this->database = $database;
        $this->query = $query;
        $this->params = array();
        $this->stmt = null;
    }

    public function setParameter(string $search, $replace): self
    {
        if (is_bool($replace)) {
            $replace = (int) $replace;
        }

        $this->params[$search] = $replace;

        return $this;
    }

    public function getResult(): self
    {
        $this->stmt = $this->database->prepare($this->query);

        return $this;
    }
}
