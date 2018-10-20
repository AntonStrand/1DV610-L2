<?php

namespace controller;

use \controller\LoginController;
use \controller\RegisterController;
use \model\Storage;
use \view\IView;
use \view\LoginView;
use \view\RegisterView;

class MainController
{
    private $storage;
    private $state;

    private $loginView;

    public function __construct(\model\Database $db)
    {
        $this->loginView = new LoginView();
        $this->storage = new Storage($db);
        $this->state = $this->storage->getSessionState($this->loginView->getUserAgent());
        $this->loginView->setState($this->state);

    }

    /**
     * Route to the matching alternative and returns the matching view.
     *
     * @param boolean $wantsToRegister
     * @return IView
     */
    public function route(bool $wantsToRegister): IView
    {
        if ($wantsToRegister) {
            $registerView = new RegisterView();
            new RegisterController($registerView, $this->storage, $this->state);
            return $registerView;
        } else {
            new LoginController($this->loginView, $this->storage, $this->state);
            return $this->loginView;
        }
    }

    public function isAuthenticated(): bool
    {
        return $this->state->isAuthenticated();
    }

    public function getUsername(): string
    {
        return $this->state->getUsername();
    }
}
