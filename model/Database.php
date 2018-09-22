<?php

namespace model;

class Database
{
    private $conn;

    public function connect(): void
    {
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

    public function saveUser(UserCredentials $user)
    {
        if (!$this->isConnected()) {
            $this->connect();
        }

        if ($this->isUsernameTaken($user)) {
            throw new \Exception("User exists, pick another username.");
        }

        echo "Go ahead and save user";
    }

    private function isUsernameTaken(UserCredentials $user): bool
    {
        $stmt = $this->prepareStatement(
            "SELECT * FROM users WHERE username=?;"
        );

        $username = $user->getUsername();
        mysqli_stmt_bind_param($stmt, "s", $username);

        mysqli_stmt_execute($stmt);

        $userFromDB = mysqli_stmt_get_result($stmt);

        return mysqli_num_rows($userFromDB) > 0;
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
}
