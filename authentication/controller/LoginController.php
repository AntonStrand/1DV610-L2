<?php

namespace authentication\controller;

use \authentication\model\SessionState;
use \authentication\model\Storage;
use \authentication\model\UserCredentials;
use \authentication\view\LoginView;

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
                $this->view->useDefaultWelcomeMessage();
            } else {
                $this->view->useLoginFailedMessage();
            }
        }
    }

    private function handleLogout(): void
    {
        if ($this->view->shouldLogout()) {
            if ($this->sessionState->isAuthenticated()) {
                $this->sessionState->logout();
                $this->storage->destroySession();
                $this->view->removeCookie();
                $this->view->useLogoutMessage();
            }
        }
    }

    private function handleSavingCookie(): void
    {
        if ($this->view->shouldSaveCookie()) {
            $cookie = $this->view->getCookieData();
            $this->storage->saveTemporaryUser($cookie);
        }
    }

    private function handleLoginByCookie(): void
    {
        if ($this->view->shouldLoginByCookie()) {
            $cookie = $this->view->getCookieData();

            if ($this->storage->isValidCookie($cookie)) {
                $this->login($cookie);
                $this->view->useLoginByCookieMessage();
            } else {
                $this->view->useCookieErrorMessage();
            }
        }
    }

    private function login(UserCredentials $userCredentials): void
    {
        if ($userCredentials->keepLoggedIn()) {
            $this->view->setCookie();
        }

        $this->sessionState->loginAs($userCredentials->getUsername());
        $this->storage->saveToSession($this->sessionState, $this->view->getUserAgent());
    }
}
