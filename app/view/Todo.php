<?php

namespace app\view;

use \app\model\Todo as TodoData;

class Todo implements View
{
    private $uniqueCompleteButton = "Todo::uniqueCompleteButton";
    private $todoData;

    public function __construct(TodoData $todoData)
    {
        $this->uniqueCompleteButton .= "::" . $todoData->getId();
        $this->todoData = $todoData;
    }

    public function isCompleted(): bool
    {
        return isset($_POST[$this->uniqueCompleteButton]) && $this->todoData->isComplete() == false;
    }

    public function getTodoData(): TodoData
    {
        return $this->todoData;
    }

    public function response(): string
    {
        $todo = $this->todoData;
        $opacity = $this->todoData->isComplete() ? '0.5' : '1';

        return '
        <form method="post">
          <fieldset style="margin-bottom: 5px; padding: 15px; opacity: ' . $opacity . '">
            <span style="margin-right: 15px;">' . $todo->getTask() . '</span>
            ' . $this->getButton() . '
          </fieldset>
          </form>
        ';
    }

    private function getButton(): string
    {
        return $this->todoData->isComplete()
        ? ''
        : '<input type="submit" name="' . $this->uniqueCompleteButton . '" value="Complete" />';
    }
}
