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

    public function getTodosForUser(string $username): array
    {
        $this->db->connect();

        $stmt = $this->db->prepareStatement(
            "SELECT * FROM " . self::$TABLE_NAME . " WHERE username=?;"
        );

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $rows = $stmt->get_result();

        $todos = array();

        foreach ($rows as $row) {
            $todos[] = new Todo($row["username"], $row["task"], $row["isComplete"], $row["id"]);
        }

        $this->db->disconnect();
        return $todos;
    }
}
