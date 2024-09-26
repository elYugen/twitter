<?php
require_once('../function.php');

class getAllHashtag {
    private $db;

    public function __construct($connect) {
        $this->db = $connect->getPDO();
        if (!$this->db) {
            error_log("Erreur: connexion à la base de données échouée");
            echo json_encode(['error' => 'Erreur de connexion à la base de données']);
            exit;
        }
    }
    
    public function getHashtag()
    {
        $query = "SELECT * from hashtags";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $hashtag = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($hashtag);
    }
}