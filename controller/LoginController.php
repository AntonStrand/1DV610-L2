<?php

namespace controller;

class LoginController
{
    private $view;

    public function __construct(\view\LoginView $view)
    {
        $this->view = $view;
        $this->handleLogin();
    }

    private function handleLogin(): void
    {
        if ($this->view->shouldLogin()) {
            $userCredentials = $this->view->getUserCredentials();
            print_r($userCredentials);
        }
    }
}
