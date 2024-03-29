<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\{Database, Manager};

class Repository
{
    protected Database $database;
    protected Manager $manager;

    public function __construct(Database $database, Manager $manager)
    {
        $this->database = $database;
        $this->manager = $manager;
    }
}
