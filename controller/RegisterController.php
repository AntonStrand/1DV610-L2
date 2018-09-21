<?php

namespace controller;

class RegisterController
{
    private $view;
    private $storage;
    private $sessionState;

    public function __construct(RegisterView $view, Storage $storage, SessionState $sessionState)
    {
        $this->view = $view;
        $this->storage = $storage;
        $this->sessionState = $sessionState;

    }
}
