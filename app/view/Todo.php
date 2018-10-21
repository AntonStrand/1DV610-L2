<?php

namespace app\view;

use \app\model\Todo as TodoData;

class Todo implements View
{
    private $todoData;

    public function __construct(TodoData $todoData)
    {
        $this->todoData = $todoData;
    }

    public function response(): string
    {
        $todo = $this->todoData;
        return '
          <fieldset style="margin-bottom: 5px; padding: 0 15px">
            <p>' . $todo->getTask() . '</p>
          </fieldset>
        ';
    }
}
