<?php
namespace Model;

use \model\exception\password\TooShortException;

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
     * Get password
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function isSame(Password $testPassword): bool
    {
        return $this->password === $testPassword->getPassword();
    }

    private function setPassword(string $password)
    {
        if ($this->isTooShort($password)) {
            throw new TooShortException("Password has too few characters, at least " . self::MIN_LENGTH . " characters.");
        }
        $this->password = $password;
    }

    private function isTooShort(string $password): bool
    {
        return strlen($password) < self::MIN_LENGTH;
    }
}
