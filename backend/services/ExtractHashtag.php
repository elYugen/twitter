<?php 

class ExtractHashtag {
    private $db;

    public function __construct($connect) {
        $this->db = $connect;
        if (!$this->db) {
            error_log("Erreur: connexion à la base de données échouée");
            echo json_encode(['error' => 'Erreur de connexion à la base de données']);
            exit;
        }
    }

    public function Extracted($hashtags, $post_id) {
        foreach ($hashtags as $tag) {
            // Vérification si le hashtag existe déjà
            $hashtag_query = "SELECT id FROM hashtags WHERE tag = :tag";
            $hashtag_stmt = $this->db->prepare($hashtag_query);
            $hashtag_stmt->bindParam(':tag', $tag);
            $hashtag_stmt->execute();
            $hashtag_id = $hashtag_stmt->fetchColumn();
    
            // si hashtag n'existe pas, l'ajouter
            if (!$hashtag_id) {
                $insert_hashtag_query = "INSERT INTO hashtags (tag) VALUES (:tag)";
                $insert_hashtag_stmt = $this->db->prepare($insert_hashtag_query);
                $insert_hashtag_stmt->bindParam(':tag', $tag);
                $insert_hashtag_stmt->execute();
                $hashtag_id = $this->db->lastInsertId();
            }
    
            // lier les hashtag a la publication
            $link_query = "INSERT INTO publication_hashtags (publication_id, hashtag_id) VALUES (:publication_id, :hashtag_id)";
            $link_stmt = $this->db->prepare($link_query);
            $link_stmt->bindParam(':publication_id', $post_id);
            $link_stmt->bindParam(':hashtag_id', $hashtag_id);
            $link_stmt->execute();
        }
    }
}