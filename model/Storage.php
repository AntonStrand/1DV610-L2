<?php

namespace model;

use \model\Session;
use \model\SessionState;

class Storage
{
    private static $SESSION_KEY = __NAMESPACE__ . __CLASS__ . "state";
    private $session;
    private $db;

    public function __construct()
    {
        $this->session = new Session();
        $this->db = new Database();
    }

    public function authenticateUser(UserCredentials $user): bool
    {
        return $this->db->isCorrectUserCredentials($user);
    }

    public function getSessionState(): SessionState
    {
        return $this->session->has(self::$SESSION_KEY)
        ? $this->session->get(self::$SESSION_KEY)
        : new SessionState(SessionState::$PRE_LOGIN);
    }

    public function saveToSession(SessionState $state): void
    {
        $this->session->set(self::$SESSION_KEY, $state);
    }

    public function saveUser(UserCredentials $user): void
    {
        $this->db->saveUser($user);
    }

    public function saveCookie(UserCredentials $cookie)
    {
        $this->db->saveCookie($cookie);
    }

    public function isValidCookie(UserCredentials $cookie): bool
    {
        return $this->db->isValidCookie($cookie);
    }

    public function destroySession(): void
    {
        $this->session->destroy();
    }
}
