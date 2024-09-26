<?php

class Connect {

    private $envHost;
    private $envName;
    private $envUser;
    private $envPassword;

    public function env() {
        if (file_exists(__DIR__ . '/.env')) {
            return parse_ini_file(__DIR__ . '/.env');
        } else {
            die('.env pas trouvé');
        }
    }

    public function __construct() {
        $env = $this->env();
        $this->envHost = $env['DB_HOST'];
        $this->envName = $env['DB_NAME'];
        $this->envUser = $env['DB_USER'];
        $this->envPassword = $env['DB_PASSWORD'];
    }

    protected function dbconnect() {
        try {
            $info = "mysql:host={$this->envHost};dbname={$this->envName};";
            $pdo = new PDO($info, $this->envUser, $this->envPassword, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            return $pdo;
        } catch (PDOException $e) {
            die("erreur de connexion : " . $e->getMessage());
        }
    }

    public function getPDO() {
        return $this->dbconnect();
    }
}
