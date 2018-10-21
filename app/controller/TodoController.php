<?php

namespace app\controller;

use \app\controller\TodoFormController;
use \app\controller\TodoListController;
use \app\model\TodoDAL;
use \app\view\TodoForm;
use \app\view\TodoList;

class TodoController
{
    private $todoDAL;
    private $todoFormCtlr;
    private $todoListCtlr;

    public function __construct(TodoDAL $todoDAL, TodoForm $todoFrom, TodoList $todoList)
    {
        $this->todoDAL = $todoDAL;
        $this->todoFormCtlr = new TodoFormController($todoFrom);
        $this->todoListCtlr = new TodoListController($todoList);
    }

    public function handleUser(string $username): void
    {
        $this->handleTodoForm($username);
        $this->handleTodoList($username);
    }

    private function handleTodoForm(string $username): void
    {
        $this->todoFormCtlr->handleTodoForm($this->todoDAL, $username);
    }

    private function handleTodoList(string $username): void
    {
        $this->todoListCtlr->handleTodoList($this->todoDAL, $username);
    }
}
