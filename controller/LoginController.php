<?php

namespace controller;

use \model\SessionState;
use \model\Storage;
use \model\UserCredentials;
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
        $this->handleSavingCookie();
        $this->handleLoginByCookie();
        $this->handleLogout();
    }

    private function handleLogin(): void
    {
        if ($this->view->shouldLogin() && !$this->sessionState->isAuthenticated()) {
            $userCredentials = $this->view->getUserCredentials();
            if ($this->storage->authenticateUser($userCredentials)) {
                $this->login($userCredentials);
            }
        }
    }

    private function handleLogout(): void
    {
        if ($this->view->shouldLogout()) {
            if ($this->sessionState->isAuthenticated()) {
                $this->view->removeCookie();
                $this->sessionState->logout();
                $this->storage->destroySession();
            }
            $this->storage->saveToSession(new SessionState());
        }
    }

    private function handleSavingCookie(): void
    {
        if ($this->view->shouldSaveCookie()) {
            $cookie = $this->view->getCookieData();
            $this->storage->saveCookie($cookie);
        }
    }

    private function handleLoginByCookie(): void
    {
        if ($this->view->shouldLoginByCookie()) {
            $cookie = $this->view->getCookieData();
            $this->sessionState->useCookies();

            if ($this->storage->isValidCookie($cookie)) {
                $this->login($cookie);
            }
        }
    }

    private function login(UserCredentials $userCredentials): void
    {
        if ($userCredentials->keepLoggedIn()) {
            $this->view->setCookie();
        }

        $this->storage->setSessionSecret();
        $this->sessionState->setUsername($userCredentials->getUsername());
        $this->sessionState->setKeepLoggedIn($userCredentials->keepLoggedIn());
        $this->sessionState->login();

        $nextState = new SessionState(
            $userCredentials->getUsername(),
            true,
            $userCredentials->keepLoggedIn()
        );

        $this->storage->saveToSession($nextState);
    }
}
