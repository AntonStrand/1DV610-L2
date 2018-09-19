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
        $this->setAuthentication($isAuth);
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

    public function setAuthentication(bool $isAuth): void
    {
        $this->isAuthenticated = $isAuth;
    }

    public function isAuthenticated(): bool
    {
        return $this->isAuthenticated;
    }

    public function isFirstLogin(): bool
    {
        return $this->reloadCounter === 0;
    }

}
