<?php

namespace model;

class TemporaryUserDAL
{
    private static $TABLE_NAME = "cookies";
    private $db;
    private $DAL;

    public function __construct(Database $db)
    {
        $this->db = $db;
        $this->DAL = new UserCredentialsDAL($db);
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
}
