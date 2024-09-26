<?php 

require_once('../function.php');

class GetPostsByHashtag {
    private $db;

    public function __construct($connect) {
        $this->db = $connect->getPDO();
        if (!$this->db) {
            error_log("Erreur: connexion à la base de données échouée");
            echo json_encode(['error' => 'Erreur de connexion à la base de données']);
            exit;
        }
    }
    public function GetPosts($hashtag)
    {
        try {

            $query = "SELECT publications.*, users.username, users.pictures, publication_image.image
        FROM publications
        JOIN publication_hashtags ON publications.id = publication_hashtags.publication_id
        JOIN hashtags ON hashtags.id = publication_hashtags.hashtag_id
        JOIN users ON publications.author_id = users.id
        LEFT JOIN publication_image ON publications.image_id = publication_image.id
        WHERE hashtags.tag = :hashtag";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':hashtag', $hashtag);
            $stmt->execute();

            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

            http_response_code(200);
            echo json_encode(['posts' => $posts]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur serveur: ' . $e->getMessage()]);
        }
    }
}