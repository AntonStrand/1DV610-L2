<?php

namespace model;

class SessionState
{
    private $username;
    private $isAuthenticated;
    private $reloadCounter;

    public function __construct(string $username = "", bool $isAuth = false, int $reloadCounter = 0)
    {
        $this->setUsername($username);
        $this->isAuthenticated = $isAuth;
        $this->reloadCounter = $reloadCounter;
    }

    public function setUsername(string $newName)
    {
        $this->username = $newName;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function login(): void
    {
        $this->isAuthenticated = true;
    }

    public function logout(): void
    {
        if ($this->isAuthenticated()) {
            $this->reloadCounter = 0;
            $this->isAuthenticated = false;
        }
    }

    public function isAuthenticated(): bool
    {
        return $this->isAuthenticated;
    }

    public function isFirstLogin(): bool
    {
        return $this->isAuthenticated() && $this->reloadCounter === 1;
    }

    public function isFirstLogout(): bool
    {
        return !$this->isAuthenticated() && $this->reloadCounter === 0;
    }

}
