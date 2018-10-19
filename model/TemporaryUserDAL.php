<?php

namespace model;

class TemporaryUserDAL
{
    private static $TABLE_NAME = "cookies";
    private $db;
    private $DAL;

    public function __construct()
    {
        $this->db = new Database();
        $this->DAL = new UserCredentialsDAL($this->db);
    }

    public function isValid(UserCredentials $user): bool
    {
        return $this->DAL->isValidCredentials(self::$TABLE_NAME, $user);
    }

    public function save(UserCredentials $user): void
    {
        $this->db->connect();
        $username = $user->getUsername();
        $password = password_hash($user->getPassword(), PASSWORD_BCRYPT);

        $stmt = $this->db->prepareStatement(
            "INSERT INTO " . self::$TABLE_NAME . " (username, password) VALUES (?, ?)
          ON DUPLICATE KEY UPDATE
          username=VALUES(username),
          password=VALUES(password);"
        );

        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $this->db->disconnect();
    }

    private function isUsernameTaken(string $username): bool
    {
        return mysqli_num_rows($this->DAL->getUser(self::$TABLE_NAME, $username)) > 0;
    }
}
