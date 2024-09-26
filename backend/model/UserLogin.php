<?php
require_once('../function.php');

class UserLogin {
    private $db;

    public function __construct($connect) {
        $this->db = $connect->getPDO(); 
    }

    public function findUserByUsername($username) {
        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); 
    }

    public function verifyPassword($password, $hashedPassword) {
        return password_verify($password, $hashedPassword); 
    }
}
