<?php
require_once('../function.php');

class UserRegister {
    private $db;

    public function __construct($connect) {
        $this->db = $connect->getPDO();
    }

    public function createUser($username, $password, $email) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash du mot de passe
        $query = "INSERT INTO users (username, password, email, created_at) VALUES (:username, :password, :email, NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':email', $email);

        return $stmt->execute();
    }
}
