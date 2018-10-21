<?php

namespace app\view;

use \app\view\Todo;

class TodoList implements View
{
    private $todoViews;

    public function __construct()
    {
        $this->todoViews = array();
    }

    public function addTodos(array $todos): void
    {
        foreach ($todos as $todo) {
            $this->todoViews[] = new Todo($todo);
        }
    }

    public function isATodoCompleted(): bool
    {
        foreach ($this->todoViews as $todo) {
            if ($todo->isCompleted()) {
                return true;
            }
        }
        return false;
    }

    public function getCompletedTodo(): \app\model\Todo
    {
        assert($this->isATodoCompleted());

        foreach ($this->todoViews as $todo) {
            if ($todo->isCompleted()) {
                return $todo->getTodoData();
            }
        }
    }

    public function response(): string
    {
        $response = '<div><h2>Your todos</h2>';

        if ($this->hasNoTodos()) {
            return $response . '<b>You don\'t have any todos yet.</b></div>';
        }

        $newestFirst = array_reverse($this->todoViews);

        foreach ($newestFirst as $todo) {
            $response .= $todo->response();
        }

        return $response . '</div>';
    }

    private function hasNoTodos(): bool
    {
        return count($this->todoViews) == 0;
    }
}
