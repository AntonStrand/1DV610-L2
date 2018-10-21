<?php

namespace app\view;

class TodoPage implements View
{
    private $todoForm;
    private $todoList;

    public function __construct(TodoForm $todoForm, TodoList $todoList)
    {
        $this->todoForm = $todoForm;
        $this->todoList = $todoList;
    }

    public function response(): string
    {
        return '
          <div>
            ' . $this->todoForm->response() . '
          </div>
          <div>
            ' . $this->todoList->response() . '
          </div>
        ';
    }
}
