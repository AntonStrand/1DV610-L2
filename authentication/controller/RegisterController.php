<?php

namespace authentication\controller;

use \authentication\model\SessionState;
use \authentication\model\Storage;
use \authentication\view\RegisterView;

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
        $this->handleRegister();
    }

    private function handleRegister(): void
    {
        if ($this->view->shouldRegister()) {
            try {
                $userCredentials = $this->view->getUserCredentials();
                $this->storage->saveUser($userCredentials);

                $nextState = new SessionState(
                    $userCredentials->getUsername()
                );

                $this->storage->saveToSession($nextState, $this->view->getUserAgent());
                header("Location: ../index.php");
            } catch (\Exception $e) {
                $this->view->usernameIsTaken();
            }
        }
    }
}
