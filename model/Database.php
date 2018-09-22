<?php

namespace model;

class Database
{
    private $conn;

    public function isCorrectUserCredentials(UserCredentials $user): bool
    {
        $username = $user->getUsername();
        $password = $user->getPassword();

        if (!$this->isConnected()) {
            $this->connect();
        }

        $userData = mysqli_fetch_assoc($this->getUser($username));

        $dbUsername = $userData["username"];
        $dbPassword = $userData["password"];

        return password_verify($password, $dbPassword) && $username === $dbUsername;

    }

    public function saveUser(string $username, string $password)
    {
        if (!$this->isConnected()) {
            $this->connect();
        }

        if ($this->isUsernameTaken($username)) {
            throw new \Exception("User exists, pick another username.");
        }

        $stmt = $this->prepareStatement(
            "INSERT INTO users (username, password) VALUES (?, ?);"
        );

        mysqli_stmt_bind_param($stmt, "ss", $username, $password);
        mysqli_stmt_execute($stmt);
    }

    private function isUsernameTaken(string $username): bool
    {
        return mysqli_num_rows($this->getUser($username)) > 0;
    }

    private function getUser(string $username): object
    {
        $stmt = $this->prepareStatement(
            "SELECT * FROM users WHERE username=?;"
        );

        mysqli_stmt_bind_param($stmt, "s", $username);

        mysqli_stmt_execute($stmt);

        return mysqli_stmt_get_result($stmt);
    }

    /**
     * Prepares and returns mysql statement.
     *
     * @param string $sql
     * @return object(mysqli_stmt)
     */
    private function prepareStatement(string $sql): object
    {
        $stmt = mysqli_stmt_init($this->conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            throw new \Exception("Statement could not be prepared.");
        }

        return $stmt;
    }

    private function isConnected(): bool
    {
        return $this->conn !== null;
    }

    private function connect(): void
    {
        //TODO: seperate settings to a env file
        $user = 'root';
        $password = 'root';
        $db = '1dv610';
        $host = 'localhost';
        $port = 8889;

        $this->conn = mysqli_connect(
            $host,
            $user,
            $password,
            $db,
            $port
        );
    }
}
