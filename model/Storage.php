<?php

namespace model;

use \model\Session;
use \model\SessionState;

class Storage
{
    private static $SESSION_KEY = __NAMESPACE__ . __CLASS__ . "state";
    private static $SESSION_SECRET = __NAMESPACE__ . __CLASS__ . "secret";
    private $session;
    private $db;

    public function __construct()
    {
        $this->session = new Session();
        $this->userDAL = new UserDAL();
        $this->tempDAL = new TemporaryUserDAL();
    }

    public function authenticateUser(UserCredentials $user): bool
    {
        return $this->userDAL->isValid($user);
    }

    public function setSessionSecret(): void
    {
        $this->session->set(self::$SESSION_SECRET, $this->getSecretString());
    }

    public function getSessionState(): SessionState
    {
        if ($this->session->has(self::$SESSION_KEY)) {
            $state = $this->session->get(self::$SESSION_KEY);
            if ($state->isAuthenticated() && $this->session->has(self::$SESSION_SECRET) && $this->session->get(self::$SESSION_SECRET) == $this->getSecretString()) {
                return $state;
            } else if (!$state->isAuthenticated()) {
                return $state;
            }
        }

        return new SessionState();
    }

    public function saveToSession(SessionState $state): void
    {
        $this->session->set(self::$SESSION_KEY, $state);
    }

    public function saveUser(UserCredentials $user): void
    {
        $this->userDAL->save($user);
    }

    public function saveCookie(UserCredentials $cookie)
    {
        $this->tempDAL->save($cookie);
    }

    public function isValidCookie(UserCredentials $cookie): bool
    {
        return $this->tempDAL->isValid($cookie);
    }

    public function destroySession(): void
    {
        $this->session->destroy();
    }

    private function getSecretString(): string
    {
        return md5($_SERVER["HTTP_USER_AGENT"] . self::$SESSION_SECRET);
    }
}
