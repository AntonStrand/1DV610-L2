<?php
namespace authentication\model;

use authentication\model\exception\username\InvalidCharactersException;
use authentication\model\exception\username\TooShortException;

class Username
{
    private const MIN_LENGTH = 3;
    private $username;

    /**
     * Create a new Username
     *
     * @throws InvalidCharactersException if the username has invalid characters
     * @throws TooShortException if the username is too short
     * @param string $username
     */
    public function __construct(string $username)
    {
        $this->setUsername($username);
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    private function setUsername(string $username): void
    {
        if ($this->hasInvalidCharacters($username)) {
            throw new InvalidCharactersException("The username has invalid characters");
        }

        if ($this->isTooShort($username)) {
            throw new TooShortException("Username has too few characters, at least " . self::MIN_LENGTH . " characters.");
        }

        $this->username = $username;
    }

    private function isTooShort(string $username): bool
    {
        return strlen($username) < self::MIN_LENGTH;
    }

    private function hasInvalidCharacters(string $username): bool
    {
        return $username !== strip_tags($username);
    }
}
