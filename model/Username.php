<?php

namespace Model;

class Username
{
    private const MIN_LENGTH = 3;
    private $username;

    /**
     * Create a new Username
     *
     * @throws Exception "Username is missing" if the username is empty
     * @throws Exception "Username has too few characters, at least 3 characters." if the username is too short
     * @param string $username
     */
    public function __construct(string $username)
    {
        $this->setUsername($username);
    }

    /**
     * Update username
     *
     * @throws Exception "Username is missing" if the username is empty
     * @throws Exception "Username has too few characters, at least 3 characters." if the username is too short
     * @param string $username
     * @return void
     */
    public function setUsername(string $username): void
    {
        $username = $this->clean($username);

        if ($this->isEmpty($username)) {
            throw new \Exception("Username is missing");
        }

        if ($this->isTooShort($username)) {
            throw new \Exception("Username has too few characters, at least " . self::MIN_LENGTH . " characters.");
        }

        $this->username = $username;
    }

    /**
     * Remove eventual tags and whitespece.
     *
     * @param string $username
     * @return string
     */
    private function clean(string $username): string
    {
        return trim(strip_tags($username));
    }

    /**
     * Checks if the username is empty
     *
     * @param string $username
     * @return boolean
     */
    private function isEmpty(string $username): bool
    {
        return empty($username);
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
}
