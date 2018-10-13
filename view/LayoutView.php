<?php

namespace view;

class LayoutView
{
    private static $register = 'register';

    public function render($isLoggedIn, IView $v, DateTimeView $dtv)
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

    private function renderIsLoggedIn($isLoggedIn)
    {
        if ($isLoggedIn) {
            return '<h2>Logged in</h2>';
        } else {
            return '<h2>Not logged in</h2>';
        }
    }

    private function renderLink(bool $isLoggedIn, IView $v): string
    {
        return ($v instanceof LoginView)
        ? ($isLoggedIn ? '' : '<a href="?register">Register a new user</a>')
        : '<a href="?">Back to login</a>';
    }
}
