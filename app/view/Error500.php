<?php

namespace app\view;

class Error500
{
    public function __construct()
    {
        echo $this->getHTML();
    }

    private function getHTML(): string
    {
        return '<!DOCTYPE html>
      <html>
        <head>
          <meta charset="utf-8">
          <title>Login Example</title>
        </head>
        <body>
          <h1>500</h1>
          <p>Sorry, something went wrong. Please try again later.</p>
         </body>
      </html>
    ';
    }
}
