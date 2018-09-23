<?php

# A wrapper for handling session.

namespace model;

class Session
{
    public function __construct()
    {
        session_start();
    }

    public function destroy(): void
    {
        session_unset();
        session_destroy();
    }

    public function has(string $name): bool
    {
        return isset($_SESSION[$name]);
    }

    public function get(string $name): SessionState
    {
        return unserialize($_SESSION[$name]);
    }

    public function set(string $name, $value)
    {
        $_SESSION[$name] = serialize($value);
    }
}
