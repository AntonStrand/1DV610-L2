<?php

/**
 * A wrapper for handling session.
 */
class Session
{
    const IS_LOGGED_IN = 'isLoggedIn';

    private static function startSession(): bool
    {
        if (session_status() == PHP_SESSION_NONE) {
            ini_set('session.cookie_httponly', true);
            if (session_start()) {
                Session::preventHijack();
                return true;
            } else {
                return false;
            }
        } else {
            Session::preventHijack();
            return true;
        }
    }

    public static function setVariable(string $name, $value): void
    {
        if (Session::startSession()) {
            $_SESSION[$name] = $value;
        }
    }

    public static function getVariable(string $name)
    {
        if (Session::startSession() && Session::variableExists($name)) {
            return $_SESSION[$name];
        }
    }

    public static function unsetVariable(string $name)
    {
        if (Session::startSession() && Session::variableExists($name)) {
            unset($_SESSION[$name]);
        }
    }

    public static function variableExists($name): bool
    {
        return isset($_SESSION[$name]);
    }

    public static function destroy(): void
    {
        session_unset();
        session_destroy();
    }

    private static function preventHijack(): void
    {
        $LAST_IP = 'lastIp';
        $REMOTE_ADDR = 'REMOTE_ADDR';
        if (isset($_SESSION[$LAST_IP]) === false) {
            $_SESSION[$LAST_IP] = $_SERVER[$REMOTE_ADDR];
        }

        if ($_SESSION[$LAST_IP] !== $_SERVER[$REMOTE_ADDR]) {
            Session::destroy();
        }
    }
}
