<?php

namespace controller;

use \controller\LoginController;
use \controller\RegisterController;
use \model\Storage;
use \view\DateTimeView;
use \view\LayoutView;
use \view\LoginView;
use \view\RegisterView;

class MainController
{
    private $storage;
    private $state;

    private $loginView;
    private $registerView;
    private $layoutView;
    private $dateTimeView;

    public function __construct()
    {
        //CREATE OBJECTS OF THE VIEWS
        $this->loginView = new LoginView();
        $this->registerView = new RegisterView();
        $this->layoutView = new LayoutView();
        $this->dateTimeView = new DateTimeView();

        $db = new \model\Database();
        $this->storage = new Storage($db);
        $this->state = $this->storage->getSessionState($this->loginView->getUserAgent());
        $this->loginView->setState($this->state);

    }

    public function route(): void
    {
        if ($this->layoutView->wantsToRegister()) {
            new RegisterController($this->registerView, $this->storage, $this->state);
            $this->layoutView->render(
                false,
                $this->registerView,
                $this->dateTimeView
            );
        } else {
            new LoginController($this->loginView, $this->storage, $this->state);
            $this->layoutView->render(
                $this->state->isAuthenticated(),
                $this->loginView,
                $this->dateTimeView
            );
        }
    }
}
