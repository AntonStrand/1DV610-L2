<?php

namespace authentication\model;

class SessionState
{
    private $isAuthenticated;
    private $username;

    public function __construct(string $username = null, bool $isAuth = false)
    {
        $this->username = $username;
        $this->isAuthenticated = $isAuth;
    }

    public function hasUsername(): bool
    {
        return $this->username != null;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function loginAs(string $username): void
    {
        $this->username = $username;
        $this->isAuthenticated = true;
    }

    public function logout(): void
    {
        $this->username = "";
        $this->isAuthenticated = false;
    }

    public function isAuthenticated(): bool
    {
        return $this->isAuthenticated;
    }
}
