<?php 

require_once('../function.php');

class SearchHashtag {

    private $db;

    public function __construct($connect) {
        $this->db = $connect->getPDO();
        if (!$this->db) {
            error_log("Erreur: connexion à la base de données échouée");
            echo json_encode(['error' => 'Erreur de connexion à la base de données']);
            exit;
        }
    }

    public function SearchHashtagOnBar($searchTerm)
    {
        $query = "SELECT * FROM hashtags WHERE tag LIKE :term ORDER BY tag ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':term', "%$searchTerm%", PDO::PARAM_STR);
        $stmt->execute();
        $hashtags = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $hashtags[] = [
                'id' => $row['id'],
                'tag' => $row['tag']
            ];
        }

        echo json_encode($hashtags);
    }
}