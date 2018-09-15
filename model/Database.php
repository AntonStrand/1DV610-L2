<?php

namespace Model;

class Database
{
    public function authenticateUser(Username $username, Password $password): bool
    {
        // TODO: Should be replaced with a proper mySQL query
        $tempUsername = 'Admin';
        $tempPassword = password_hash('Password', PASSWORD_BCRYPT);

        $username = $username->getUsername();
        $password = $password->getPassword();

        return ($tempUsername == $username) && password_verify($password, $tempPassword);
    }
}
