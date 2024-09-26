<?php 

require_once('../function.php');

class GetAllPostsFromUser {

    private $db;

    public function __construct($connect) {
        $this->db = $connect->getPDO();
        if (!$this->db) {
            error_log("Erreur: connexion à la base de données échouée");
            echo json_encode(['error' => 'Erreur de connexion à la base de données']);
            exit;
        }
    }

    public function GetPostFromUser($userId) {
        $query = "SELECT 
        publications.id, 
        publications.author_id, 
        publications.content, 
        publications.publishdate, 
        publications.image_id, 
        users.username, 
        users.pictures, 
        publication_image.image,
        (SELECT COUNT(*) FROM comments WHERE comments.publication_id = publications.id) AS comment_count
    FROM publications
    JOIN users ON publications.author_id = users.id
    LEFT JOIN publication_image ON publications.image_id = publication_image.id
    WHERE publications.author_id = :id
    ORDER BY publications.publishdate DESC";
    
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($posts);
    }
}