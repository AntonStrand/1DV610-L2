<?php

namespace model;

class UserCredentialsDAL
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function isValidCredentials(string $tableName, UserCredentials $credentials): bool
    {
        $this->db->connect();
        $username = $credentials->getUsername();
        $password = $credentials->getPassword();

        $userData = mysqli_fetch_assoc($this->getUser($tableName, $username));

        $dbUsername = $userData["username"];
        $dbPassword = $userData["password"];

        $this->db->disconnect();
        return password_verify($password, $dbPassword) && $username === $dbUsername;
    }

    public function getUser(string $tableName, string $username): object
    {
        $stmt = $this->db->prepareStatement(
            "SELECT * FROM " . $tableName . " WHERE username=?;"
        );

        $stmt->bind_param("s", $username);
        $stmt->execute();
        return mysqli_stmt_get_result($stmt);
    }

}
