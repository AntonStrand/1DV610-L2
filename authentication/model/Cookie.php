<?php

namespace authentication\model;

/**
 * A cookie wrapper to simplify handling cookies
 */

class Cookie
{
    public function set(string $name, string $value, int $lifeSpanFromNow): void
    {
        \setcookie($name, $value, time() + $lifeSpanFromNow, "/", "", "", true);

        // To make the cookie available during this request.
        $_COOKIE[$name] = $value;
    }

    public function delete(string $name): void
    {
        \setcookie($name, null, time() - 1000, "/", "", "", true);

        // Remove the cookie instantly.
        $_COOKIE[$name] = null;
    }

    public function has(string $name): bool
    {
        return isset($_COOKIE[$name]);
    }

    public function get(string $name): string
    {
        assert($this->has($name));
        return $_COOKIE[$name];
    }
}
