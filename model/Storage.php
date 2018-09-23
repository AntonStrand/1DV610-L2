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
        // TODO: Test if there is a matching user or return a new user.
        if ($this->session->has(self::$SESSION_KEY)) {
            $prevState = $this->session->get(self::$SESSION_KEY);

            // if ($prevState->getStatus() === SessionState::$FIRST_LOGIN) {
            //     return new SessionState(SessionState::$POST_LOGIN, $prevState->getUsername(), true);
            // }

            return $prevState;
        }

        return new SessionState(SessionState::$PRE_LOGIN);
    }

    public function saveToSession(SessionState $state): void
    {
        $this->session->set(self::$SESSION_KEY, $state);
    }

    public function saveUser(UserCredentials $user): void
    {
        $username = $user->getUsername();
        $password = password_hash($user->getPassword(), PASSWORD_BCRYPT);

        $this->db->saveUser($username, $password);
    }

    public function destroySession(): void
    {
        $this->session->destroy();
    }
}
