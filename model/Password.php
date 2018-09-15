<?php

namespace Model;

class Password
{
    private const MIN_LENGTH = 6;
    private $password;

    /**
     * Create a new Password
     *
     * @throws Exception "Password is missing" if the $password is empty
     * @throws Exception "Password has too few characters, at least 6 characters." if the $password is too short
     * @param string $password
     */
    public function __construct(string $password)
    {
        $this->setPassword($password);
    }

    /**
     * Update password
     *
     * @throws Exception "Password is missing" if the $password is empty
     * @throws Exception "Password has too few characters, at least 6 characters." if the $password is too short
     * @param string $password
     * @return void
     */
    public function setPassword(string $password)
    {
        $password = $this->clean($password);

        if ($this->isEmpty($password)) {
            throw new \Exception("Password is missing");
        }

        if ($this->isTooShort($password)) {
            throw new \Exception("Password has too few characters, at least " . self::MIN_LENGTH . " characters.");
        }

        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Remove eventual tags and whitespece.
     *
     * @param string $password
     * @return string
     */
    private function clean(string $password): string
    {
        return trim(strip_tags($password));
    }

    /**
     * Check if the password is empty
     *
     * @param string $password
     * @return boolean
     */
    private function isEmpty(string $password): bool
    {
        return empty($password);
    }

    /**
     * Checks if the password is too short
     *
     * @param string $password
     * @return boolean
     */
    private function isTooShort(string $password): bool
    {
        return strlen($password) < self::MIN_LENGTH;
    }
}