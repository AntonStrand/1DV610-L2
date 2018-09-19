<?php

namespace model;

class SessionState
{
    private $username;
    private $isAuthenticated;

    public function __construct(string $username = "", bool $isAuth = false)
    {
        $this->setUsername($username);
        $this->setAuthentication($isAuth);
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

}
