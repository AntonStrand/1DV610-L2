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
        $this->storage = new Storage();
        $this->state = $this->storage->getSessionState();

        //CREATE OBJECTS OF THE VIEWS
        $this->loginView = new LoginView($this->state);
        $this->registerView = new RegisterView();
        $this->layoutView = new LayoutView();
        $this->dateTimeView = new DateTimeView();
    }

    public function route(): void
    {
        echo $this->state->isAuthenticated() ? "Yes" : "No";

        if ($this->layoutView->wantsToRegister()) {
            new RegisterController($this->registerView, $this->storage, $this->state);
            $this->layoutView->render(
                false,
                $this->registerView,
                $this->dateTimeView
            );
        } else if (!$this->state->isAuthenticated()) {
            //TODO: change order to see if it solves update problem
            new LoginController($this->loginView, $this->storage, $this->state);
            $this->layoutView->render(
                $this->state->isAuthenticated(),
                $this->loginView,
                $this->dateTimeView
            );
        } else {
            echo "welcome";
            new LoginController($this->loginView, $this->storage, $this->state);
            $this->layoutView->render(
                $this->state->isAuthenticated(),
                $this->loginView,
                $this->dateTimeView
            );
        }
    }
}
