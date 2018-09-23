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
        if ($this->view->shouldLogin() && !$this->sessionState->isAuthenticated()) {
            $userCredentials = $this->view->getUserCredentials();
            if ($this->storage->authenticateUser($userCredentials)) {
                $this->sessionState->setUsername($userCredentials->getUsername());
                $this->sessionState->login();
            }
            $nextState = new SessionState(
                SessionState::$POST_LOGIN,
                $userCredentials->getUsername(),
                true
            );
            $this->storage->saveToSession($nextState);
        }
    }

    private function handleLogout(): void
    {
        if ($this->view->shouldLogout() && $this->sessionState->isAuthenticated()) {
            $this->sessionState->logout();
            $this->storage->destroySession();
        } else if ($this->view->shouldLogout()) {
            $nextState = new SessionState(SessionState::$PRE_LOGIN, "", false);
            $this->storage->saveToSession($nextState);
        }
    }
}
