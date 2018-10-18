<?php

namespace model;

class SessionState
{
    private $isAuthenticated;
    private $username;
    private $keepLoggedIn;
    private $isUsingCookies;

    // TODO: Use Username instead of string.
    public function __construct(string $username = "", bool $isAuth = false, bool $keepLoggedIn = false)
    {
        $this->setUsername($username);
        $this->isAuthenticated = $isAuth;
        $this->keepLoggedIn = $keepLoggedIn;
        $this->isUsingCookies = false;
    }

    public function useCookies(): void
    {
        $this->isUsingCookies = true;
    }

    public function isUsingCookies(): bool
    {
        return $this->isUsingCookies;
    }

    public function hasUsername(): bool
    {
        return strlen($this->username) > 0;
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
        $this->isAuthenticated = false;
        $this->keepLoggedIn = false;
    }

    public function isAuthenticated(): bool
    {
        return $this->isAuthenticated;
    }

    public function setKeepLoggedIn(bool $shouldKeep): void
    {
        $this->keepLoggedIn = $shouldKeep;
    }

    public function keepLoggedIn(): bool
    {
        return $this->keepLoggedIn;
    }
}
