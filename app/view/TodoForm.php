<?php

namespace app\view;

class TodoForm implements View
{
    private static $task = "TodoForm::task";
    private static $submit = "TodoForm::submit";

    private $hasError = false;

    public function shouldSaveTodo(): bool
    {
        return $this->hasTask() && $this->hasClickedSubmit();
    }

    public function getTask(): string
    {
        assert($this->shouldSaveTodo());
        return $this->getRequestTodo();
    }

    public function showErrorMessage(): void
    {
        $this->hasError = true;
    }

    public function response(): string
    {
        return $this->generateHTML();
    }

    private function generateHTML(): string
    {
        return '
          <form method="post" >
            <fieldset>
              <legend>Add a todo</legend>
              ' . $this->getErrorMessage() . '
              <label for="' . self::$task . '">Task :</label>
              <input type="text" id="' . self::$task . '" name="' . self::$task . '" value="' . $this->getRequestTodo() . '" />
              <input type="submit" name="' . self::$submit . '" value="Add todo" />
            </fieldset>
          </form>
        ';
    }

    private function getErrorMessage(): string
    {
        return $this->hasError ? '<p style="color: red">The todo couldn\'t be saved</p>' : '';
    }

    private function hasTask(): bool
    {
        return isset($_POST[self::$task]) && strlen(trim($_POST[self::$task])) > 0;
    }

    private function getRequestTodo(): string
    {
        return isset($_POST[self::$task])
        ? strip_tags($_POST[self::$task])
        : '';
    }

    private function hasClickedSubmit(): bool
    {
        return isset($_POST[self::$submit]);
    }
}
