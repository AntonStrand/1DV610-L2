<?php

namespace app\view;

use \app\view\Todo;

class TodoList implements View
{
    private $todoViews;

    public function __construct(array $todos)
    {
        $this->todoViews = array();

        foreach ($todos as $todo) {
            $this->todoViews[] = new Todo($todo);
        }
    }

    public function response(): string
    {
        $response = '<div><h2>Your todos</h2>';

        if ($this->hasNoTodos()) {
            return $response . '<b>You don\'t have any todos yet.<b></div>';
        }

        foreach ($this->todoViews as $todo) {
            $response .= $todo->response();
        }

        return $response . '</div>';
    }

    private function hasNoTodos(): bool
    {
        return count($this->todoViews) == 0;
    }
}
