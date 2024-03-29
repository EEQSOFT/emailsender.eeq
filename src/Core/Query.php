<?php

declare(strict_types=1);

namespace App\Core;

class Query
{
    protected string $query;

    public function __construct(string $query)
    {
        $this->query = $query;
    }

    public function setParameter(string $search, $replace): self
    {
        if ($replace === false) {
            $replace = '0';
        }

        $this->query = str_replace(
            ':' . $search,
            addslashes((string) $replace),
            $this->query
        );

        return $this;
    }

    public function getStrQuery(): string
    {
        return $this->query;
    }
}
