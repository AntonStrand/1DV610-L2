<?php

namespace app\model;

class Database
{
    private $connection;

    public function connect(): void
    {
        if (!$this->isConnected()) {
            $this->connection = new \Mysqli(
                \Settings::$HOST,
                \Settings::$USER,
                \Settings::$PASSWORD,
                \Settings::$DB
            );
        }
    }

    public function disconnect(): void
    {
        $this->connection->close();
        $this->connection = null;
    }

    public function prepareStatement(string $sql): object
    {
        assert($this->isConnected());

        $stmt = $this->connection->stmt_init();

        if (!$stmt->prepare($sql)) {
            throw new \Exception("Statement could not be prepared.");
        }

        return $stmt;
    }

    private function isConnected(): bool
    {
        return $this->connection !== null;
    }

}
