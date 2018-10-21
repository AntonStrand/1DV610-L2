<?php

namespace authentication\model;

use \authentication\model\Session;
use \authentication\model\SessionState;

class Storage
{
    private static $SESSION_KEY = __NAMESPACE__ . __CLASS__ . "state";
    private static $SESSION_SECRET = __NAMESPACE__ . __CLASS__ . "secret";
    private $session;
    private $userDAL;
    private $tempDAL;

    public function __construct(\app\model\Database $db)
    {
        $this->session = new Session();
        $this->tempDAL = new TemporaryUserDAL($db);
        $this->userDAL = new UserDAL($db);
    }

    public function authenticateUser(UserCredentials $user): bool
    {
        return $this->userDAL->isValid($user);
    }

    public function getSessionState(string $fingerPrint): SessionState
    {
        if ($this->session->has(self::$SESSION_KEY)) {
            $state = $this->session->get(self::$SESSION_KEY);

            if ($this->shouldUseState($state, $fingerPrint)) {
                return $state;
            }
        }

        return new SessionState();
    }

    public function saveToSession(SessionState $state, string $fingerPrint): void
    {
        $this->setSessionSecret($fingerPrint);
        $this->session->set(self::$SESSION_KEY, $state);
    }

    public function saveUser(UserCredentials $user): void
    {
        $this->userDAL->save($user);
    }

    public function saveTemporaryUser(UserCredentials $userCredentials)
    {
        $this->tempDAL->save($userCredentials);
    }

    public function isValidCookie(UserCredentials $cookie): bool
    {
        return $this->tempDAL->isValid($cookie);
    }

    public function destroySession(): void
    {
        $this->session->destroy();
    }

    private function setSessionSecret(string $fingerPrint): void
    {
        $this->session->set(self::$SESSION_SECRET, $this->getSecretString($fingerPrint));
    }

    private function getSecretString(string $fingerPrint): string
    {
        return md5($fingerPrint . self::$SESSION_SECRET);
    }

    private function shouldUseState(SessionState $state, string $fingerPrint): bool
    {
        return ($state->isAuthenticated() && $this->isUntouchedSession($fingerPrint)) || !$state->isAuthenticated();
    }

    private function isUntouchedSession(string $fingerPrint): bool
    {
        return $this->session->has(self::$SESSION_SECRET)
        && $this->session->get(self::$SESSION_SECRET) == $this->getSecretString($fingerPrint);
    }
}
