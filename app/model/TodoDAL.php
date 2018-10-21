<?php

namespace app\model;

class TodoDAL
{
    private static $TABLE_NAME = "todos";
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function save(Todo $todo): void
    {
        $this->db->connect();

        $username = $todo->getUsername();
        $task = $todo->getTask();
        $isComplete = intval($todo->isComplete());

        $stmt = $this->db->prepareStatement(
            "INSERT INTO " . self::$TABLE_NAME . " (username, task, isComplete) VALUES (?, ?, ?);"
        );

        $stmt->bind_param("ssi", $username, $task, $isComplete);
        $stmt->execute();
        $this->db->disconnect();
    }
}
