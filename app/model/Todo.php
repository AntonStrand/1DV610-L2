<?php

namespace app\model;

class Todo
{
    private $username;
    private $task;
    private $isComplete;
    private $id;

    public function __construct(string $username, string $task, bool $isComplete, int $id = 0)
    {
        $this->username = $username;
        $this->task = $task;
        $this->isComplete = $isComplete;
        $this->id = $id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getTask(): string
    {
        return $this->task;
    }

    public function isComplete(): bool
    {
        return $this->isComplete;
    }
}
