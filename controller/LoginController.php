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
        $this->handleLogout();
        $this->handleSavingCookie();
        $this->handleLoginByCookie();
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
            $nextState = new SessionState(SessionState::$PRE_LOGIN, "", false, false);
            $this->storage->saveToSession($nextState);
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

        $this->sessionState->setUsername($userCredentials->getUsername());
        $this->sessionState->login();
        $this->sessionState->setKeepLoggedIn($userCredentials->keepLoggedIn());

        $nextState = new SessionState(
            SessionState::$POST_LOGIN,
            $userCredentials->getUsername(),
            true,
            $userCredentials->keepLoggedIn()
        );

        $this->storage->saveToSession($nextState);
    }
}
