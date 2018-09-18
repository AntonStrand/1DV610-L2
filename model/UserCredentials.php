<?php

namespace Model;

class UserCredentials
{
    private $username;
    private $password;
    private $keepLoggedIn;

    /**
     * Create a instance of a UserCredentials
     *
     * @throws \Exception
     * @param string $username
     * @param string $password
     * @param boolean $keepLoggedIn
     */
    public function __construct(string $username, string $password, bool $keepLoggedIn)
    {
        $this->username = new Username($username);
        $this->password = new Password($password);
        $this->keepLoggedIn = $keepLoggedIn;
    }
}
