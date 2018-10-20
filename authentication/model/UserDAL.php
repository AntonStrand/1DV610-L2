<?php

namespace model;

class UserDAL
{
    private static $TABLE_NAME = "users";
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

        if ($this->isUsernameTaken($username)) {
            throw new \Exception("Username is taken");
        }

        $stmt = $this->db->prepareStatement(
            "INSERT INTO " . self::$TABLE_NAME . " (username, password) VALUES (?, ?);"
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
