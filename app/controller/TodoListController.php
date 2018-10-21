<?php

namespace app\controller;

use \app\model\TodoDAL;
use \app\view\TodoList;

class TodoListController
{
    private $todoList;

    public function __construct(TodoList $todoList)
    {
        $this->todoList = $todoList;
    }

    public function handleTodoList(TodoDAL $todoDAL, string $username): void
    {
        $todos = $todoDAL->getTodosForUser($username);
        $this->todoList->addTodos($todos);
        $this->handleTodoChange($todoDAL);
    }

    private function handleTodoChange(TodoDAL $todoDAL): void
    {
        if ($this->todoList->isATodoCompleted()) {
            $todo = $this->todoList->getCompletedTodo();
            $todoDAL->saveAsCompleted($todo);
        }
    }
}
