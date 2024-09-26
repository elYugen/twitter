<?php

namespace App\vendor;

class Dbconnect {
    private static $instance = null;
    private $db;

    private function __construct($connect) {
        $this->db = $connect;
    }

    public static function getInstance($connect = null) {
        if (self::$instance === null) {
            if ($connect === null) {
                throw new \Exception("Connexion non fournie");
            }
            self::$instance = new Dbconnect($connect);
        }

        return self::$instance;
    }

    public function getConnection() {
        return $this->db;
    }
}
