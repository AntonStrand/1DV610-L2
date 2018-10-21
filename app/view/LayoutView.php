<?php

namespace app\view;

class LayoutView
{
    private static $register = 'register';

    public function render($isLoggedIn, \authentication\view\View $v, DateTimeView $dtv, View $mainPage = null)
    {
        echo '<!DOCTYPE html>
      <html>
        <head>
          <meta charset="utf-8">
          <title>Login Example</title>
        </head>
        <body>
          <h1>Assignment 2</h1>
          ' . $this->renderLink($isLoggedIn, $v) . '
          ' . $this->renderIsLoggedIn($isLoggedIn) . '

          <div class="container">
              ' . $v->response() . '
              ' . $this->renderMainPage($mainPage) . '
              ' . $dtv->show() . '
          </div>
         </body>
      </html>
    ';
    }

    public function wantsToRegister(): bool
    {
        return isset($_GET[self::$register]);
    }

    private function renderIsLoggedIn(bool $isLoggedIn)
    {
        if ($isLoggedIn) {
            return '<h2>Logged in</h2>';
        } else {
            return '<h2>Not logged in</h2>';
        }
    }

    private function renderLink(bool $isLoggedIn, \authentication\view\View $v): string
    {
        return ($v instanceof \authentication\view\LoginView)
        ? ($isLoggedIn ? '' : '<a href=?' . self::$register . '>Register a new user</a>')
        : '<a href="?">Back to login</a>';
    }

    private function renderMainPage(View $main = null): string
    {
        return ($main != null)
        ? $main->response()
        : '';

    }
}
