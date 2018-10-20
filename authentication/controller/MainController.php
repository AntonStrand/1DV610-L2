<?php

namespace controller;

use \controller\LoginController;
use \controller\RegisterController;
use \model\Storage;
use \view\LoginView;
use \view\RegisterView;

class MainController
{
    private $storage;
    private $state;

    private $loginView;

    public function __construct()
    {
        $this->loginView = new LoginView();
        $db = new \model\Database();
        $this->storage = new Storage($db);
        $this->state = $this->storage->getSessionState($this->loginView->getUserAgent());
        $this->loginView->setState($this->state);

    }

    public function route(bool $wantsToRegister)
    {
        if ($wantsToRegister) {
            $registerView = new RegisterView();
            new RegisterController($registerView, $this->storage, $this->state);
            return $registerView;
        } else {
            new LoginController($this->loginView, $this->storage, $this->state);
            return $this->loginView;
            // $this->layoutView->render(
            //     $this->state->isAuthenticated(),
            //     $this->loginView,
            //     $this->dateTimeView
            // );
        }
    }
}
