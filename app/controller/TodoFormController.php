<?php

namespace app\controller;

use \app\model\Todo;
use \app\model\TodoDAL;
use \app\view\TodoForm;

class TodoFormController
{
    private $todoForm;

    public function __construct(TodoForm $todoForm)
    {
        $this->todoForm = $todoForm;
    }

    public function handleTodoForm(TodoDAL $todoDAL, string $username): void
    {
        if ($this->todoForm->shouldSaveTodo()) {
            try {
                $todoDAL->save(
                    new Todo(
                        $username,
                        $this->todoForm->getTask(),
                        false
                    )
                );

                // Make sure that the todo isn't saved again on reload
                header("Location: ../index.php");
            } catch (\Exception $e) {
                $this->todoForm->showErrorMessage();
            }
        }
    }
}
