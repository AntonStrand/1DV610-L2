<?php

namespace app\model;

class Todo
{
    private $username;
    private $task;
    private $isComplete;

    public function __construct(string $username, string $task, bool $isComplete)
    {
        $this->username = $username;
        $this->task = $task;
        $this->isComplete = $isComplete;
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
