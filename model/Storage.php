<?php

namespace model;

use \model\Session;
use \model\SessionState;

class Storage
{
    private $session;

    public function __construct()
    {
        $this->session = new \model\Session();
    }

    public function authenticateUser(UserCredentials $user): bool
    {
        // TODO: Should be replaced with a proper mySQL query
        $tempUsername = 'Admin';
        $tempPassword = password_hash('Password', PASSWORD_BCRYPT);

        $username = $user->getUsername();
        $password = $user->getPassword();

        return ($tempUsername == $username) && password_verify($password, $tempPassword);
    }

    public function getSessionState(): SessionState
    {
        $this->session->increaseReloadCounter();
        $reloads = $this->session->getReloadCounter();

        // TODO: Test if there is a matching user or return a new user.
        if ($this->session->has("username")) {
            return new SessionState($this->session->get("username"), true, $reloads);
        }
        return new SessionState('', false, $reloads);
    }

    public function saveToSession(UserCredentials $user): void
    {
        if ($this->authenticateUser($user)) {
            $this->session->set("username", $user->getUsername());
            $this->session->set("username", $user->getUsername());
        }
    }

    public function destroySession(): void
    {
        $this->session->destroy();
    }
}
