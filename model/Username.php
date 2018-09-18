<?php
namespace model;

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
        if (empty($username)) {
            throw new \Exception("Username is missing");
        }
        if ($this->isTooShort($username)) {
            throw new \LengthException("Username has too few characters, at least " . self::MIN_LENGTH . " characters.");
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
}
