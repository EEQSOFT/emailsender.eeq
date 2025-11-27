<?php

declare(strict_types=1);

namespace App\Core;

class Database
{
    protected string $dbHost;
    protected int $dbPort;
    protected string $dbUser;
    protected string $dbPassword;
    protected string $dbDatabase;
    protected string $dbNames;
    protected $pdo;
    protected $stmt;

    public function __construct()
    {
        $database = require(DATABASE_FILE);

        $this->dbHost = $database['db_host'];
        $this->dbPort = $database['db_port'];
        $this->dbUser = $database['db_user'];
        $this->dbPassword = $database['db_password'];
        $this->dbDatabase = $database['db_database'];
        $this->dbNames = $database['db_names'];
        $this->pdo = null;
        $this->stmt = null;
    }

    public function connect(): void
    {
        try {
            $this->pdo = new \PDO(
                'mysql:host=' . $this->dbHost . ':'
                    . $this->dbPort . ';charset=' . $this->dbNames . ';'
                    . 'dbname=' . $this->dbDatabase,
                $this->dbUser,
                $this->dbPassword
            );
        } catch (\PDOException $e) {
            die('Could not connect to the database');
        }

        $this->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function prepare(string $query)
    {
        $this->stmt = $this->pdo->prepare($query);

        return $this->stmt;
    }

    public function execute(array $array = array()): bool
    {
        return $this->stmt->execute($array);
    }

    public function rowCount(): int
    {
        return $this->stmt->rowCount();
    }

    public function lastInsertId(): int
    {
        return (int) $this->pdo->lastInsertId();
    }

    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    public function rollBack(): bool
    {
        return $this->pdo->rollBack();
    }
}
