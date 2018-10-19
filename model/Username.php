<?php
namespace model;

use \model\exception\username\InvalidCharactersException;
use \model\exception\username\TooShortException;

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

    /**
     * Update username
     *
     * @throws InvalidCharactersException if the username has invalid characters
     * @throws TooShortException if the username is too short
     * @param string $username
     * @return void
     */
    public function setUsername(string $username): void
    {
        if ($this->hasInvalidCharacters($username)) {
            throw new InvalidCharactersException("The username has invalid characters");
        }

        if ($this->isTooShort($username)) {
            throw new TooShortException("Username has too few characters, at least " . self::MIN_LENGTH . " characters.");
        }

        $this->username = $username;
    }

    /**
     * The username
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Checks if the username is too short
     *
     * @param string $username
     * @return boolean
     */
    private function isTooShort(string $username): bool
    {
        return strlen($username) < self::MIN_LENGTH;
    }

    private function hasInvalidCharacters(string $username): bool
    {
        return $username !== strip_tags($username);
    }
}
