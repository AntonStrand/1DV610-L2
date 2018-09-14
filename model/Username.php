<?php

namespace Model;

class Username
{
    private const MIN_LENGTH = 3;
    private $username;

    public function __construct(string $username)
    {
        $this->setUsername($username);
    }

    public function setUsername(string $username)
    {
        $username = $this->clean($username);

        if ($this->isEmpty($username)) {
            throw new \Exception("Username is missing");
        }

        if ($this->isToShort($username)) {
            throw new \Exception("Username has too few characters, at least " . self::MIN_LENGTH . " characters.");
        }

        $this->username = $username;
    }

    private function clean($username): string
    {
        return trim(strip_tags($username));
    }

    private function isEmpty(string $username): bool
    {
        return empty($username);
    }

    private function isToShort(string $username): bool
    {
        return strlen($username) < self::MIN_LENGTH;
    }
}
