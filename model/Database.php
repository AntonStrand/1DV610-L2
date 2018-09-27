<?php

namespace model;

class Database
{
    private $conn;

    public function isCorrectUserCredentials(UserCredentials $user): bool
    {
        return $this->isValidCredentials("users", $user);
    }

    public function isValidCookie(UserCredentials $cookie): bool
    {
        return $this->isValidCredentials("cookies", $cookie);
    }

    public function saveCookie(UserCredentials $cookie): void
    {
        $this->connect();
        $username = $cookie->getUsername();
        $password = password_hash($cookie->getPassword(), PASSWORD_BCRYPT);

        $stmt = $this->prepareStatement(
            "INSERT INTO cookies (username, password) VALUES (?, ?)
            ON DUPLICATE KEY UPDATE
            username=VALUES(username),
            password=VALUES(password);"
        );

        mysqli_stmt_bind_param($stmt, "ss", $username, $password);
        mysqli_stmt_execute($stmt);
    }

    public function saveUser(UserCredentials $user): void
    {
        $this->connect();
        $username = $user->getUsername();
        $password = password_hash($user->getPassword(), PASSWORD_BCRYPT);

        if ($this->isUsernameTaken($username)) {
            throw new \Exception("User exists, pick another username.");
        }

        $stmt = $this->prepareStatement(
            "INSERT INTO users (username, password) VALUES (?, ?);"
        );

        mysqli_stmt_bind_param($stmt, "ss", $username, $password);
        mysqli_stmt_execute($stmt);
    }

    private function isValidCredentials(string $tableName, UserCredentials $credentials): bool
    {
        $this->connect();

        $username = $credentials->getUsername();
        $password = $credentials->getPassword();

        $userData = mysqli_fetch_assoc($this->getUser($tableName, $username));

        $dbUsername = $userData["username"];
        $dbPassword = $userData["password"];

        return password_verify($password, $dbPassword) && $username === $dbUsername;
    }

    private function isUsernameTaken(string $username): bool
    {
        return mysqli_num_rows($this->getUser("users", $username)) > 0;
    }

    private function getUser(string $tableName, string $username): object
    {
        $stmt = $this->prepareStatement(
            "SELECT * FROM " . $tableName . " WHERE username=?;"
        );

        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    }

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
            $this->conn = mysqli_connect(
                \Settings::$HOST,
                \Settings::$USER,
                \Settings::$PASSWORD,
                \Settings::$DB,
                \Settings::$PORT
            );
        }
    }
}
