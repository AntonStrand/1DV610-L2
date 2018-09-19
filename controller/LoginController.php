<?php

namespace controller;

use \model\SessionState;
use \model\Storage;
use \view\LoginView;

class LoginController
{
    private $view;
    private $storage;
    private $sessionState;

    public function __construct(LoginView $view, Storage $storage, SessionState $sessionState)
    {
        $this->view = $view;
        $this->storage = $storage;
        $this->sessionState = $sessionState;
        $this->handleLogin();
        $this->handleLogout();
    }

    private function handleLogin(): void
    {
        if ($this->view->shouldLogin()) {
            $userCredentials = $this->view->getUserCredentials();
            if ($this->storage->authenticateUser($userCredentials)) {
                $this->sessionState->setUsername($userCredentials->getUsername());
                $this->sessionState->setAuthentication(true);
            }
            $this->storage->saveToSession($userCredentials);
        }
    }

    private function handleLogout(): void
    {
        if ($this->view->shouldLogout()) {
            $this->sessionState->setAuthentication(false);
            $this->storage->destroySession();
        }
    }
}
