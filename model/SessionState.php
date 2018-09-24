<?php

namespace model;

class SessionState
{
    private $isAuthenticated;
    private $status;
    private $username;
    private $keepLoggedIn;
    private $isUsingCookies;

    public static $NEW_USER = "NEW_USER";
    public static $FIRST_LOGIN = "FIRST_LOGIN";
    public static $POST_LOGIN = "POST_LOGIN";
    public static $PRE_LOGIN = "PRE_LOGIN";
    public static $LOGOUT = "LOGOUT";

    public function __construct(string $status, string $username = "", bool $isAuth = false, bool $keepLoggedIn = false)
    {
        $this->setUsername($username);
        $this->isAuthenticated = $isAuth;
        $this->status = $status;
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
        $this->status = self::$FIRST_LOGIN;
    }

    public function logout(): void
    {
        $this->isAuthenticated = false;
        $this->keepLoggedIn = false;
        $this->status = self::$LOGOUT;
    }

    public function isAuthenticated(): bool
    {
        return $this->isAuthenticated;
    }

    public function isFirstLogin(): bool
    {
        return $this->isAuthenticated() && $this->status === self::$FIRST_LOGIN;
    }

    public function isFirstLogout(): bool
    {
        return $this->status === self::$LOGOUT;
    }

    public function isNewUser(): bool
    {
        return $this->status === self::$NEW_USER;
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
