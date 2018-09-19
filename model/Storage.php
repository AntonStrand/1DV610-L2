<?php

namespace model;

use \model\Session;
use \model\User;

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

    public function getUser(): User
    {
        // TODO: Test if there is a matching user or return a new user.
        if ($this->session->has("username")) {
            echo "Oh, yes it has";
            return new User($this->session->get("username"), true);
        }
        return new User();
    }

    public function saveToSession(UserCredentials $user): void
    {
        if ($this->authenticateUser($user)) {
            $this->session->set("username", $user->getUsername());
        }
    }

    public function destroySession(): void
    {
        $this->session->destroy();
    }
}
