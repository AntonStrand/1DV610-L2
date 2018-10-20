<?php

namespace app\view;

class TodoForm implements View
{
    private static $todo = "TodoForm::todo";
    private static $submit = "TodoForm::submit";

    public function shouldSaveTodo(): bool
    {
        return $this->hasTodo() && $this->hasClickedSubmit();
    }

    public function getTodo(): string
    {
        assert($this->shouldSaveTodo());
        return $this->getRequestTodo();
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
              <label for="' . self::$todo . '">Username :</label>
              <input type="text" id="' . self::$todo . '" name="' . self::$todo . '" value="' . $this->getRequestTodo() . '" />
              <input type="submit" name="' . self::$submit . '" value="save" />
            </fieldset>
          </form>
        ';
    }

    private function hasTodo(): bool
    {
        return isset($_POST[self::$todo]);
    }

    private function getRequestTodo(): string
    {
        return isset($_POST[self::$todo])
        ? strip_tags($_POST[self::$todo])
        : '';
    }

    private function hasClickedSubmit(): bool
    {
        return isset($_POST[self::$submit]);
    }
}
