<?php

namespace model;

class Database
{
    private $conn;

    public function isCorrectUserCredentials(UserCredentials $user): bool
    {
        $this->connect();

        $username = $user->getUsername();
        $password = $user->getPassword();

        $userData = mysqli_fetch_assoc($this->getUser($username));

        $dbUsername = $userData["username"];
        $dbPassword = $userData["password"];

        return password_verify($password, $dbPassword) && $username === $dbUsername;
    }

    public function saveCookie(): void
    {
        $this->connect();

        $this->insertTo("cookies", $username, $password);
    }

    public function saveUser(string $username, string $password)
    {
        $this->connect();

        if ($this->isUsernameTaken($username)) {
            throw new \Exception("User exists, pick another username.");
        }

        $this->insertTo("users", $username, $password);
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
        if (!$this->isConnected()) {
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

    /**
     * Insert to provided table
     *
     * @param string $where the name of the table
     * @param string $username of the user
     * @param string $password of the user
     * @return void
     */
    private function insertTo(string $where, string $username, string $password): void
    {
        $stmt = $this->prepareStatement(
            "INSERT INTO " . $where . " (username, password) VALUES (?, ?);"
        );

        mysqli_stmt_bind_param($stmt, "ss", $username, $password);
        mysqli_stmt_execute($stmt);
    }
}
