<?php

require_once('../function.php');

class CreateComment {
    private $db;
    public $image_url = null;
    private $max_file_size = 10 * 1024 * 1024; // Taille maximale des fichiers (10 Mo)
    private $allowed_extensions = ['png', 'jpg', 'jpeg', 'webp', 'gif']; // Extensions autorisées

    public function __construct($connect) {
        $this->db = $connect->getPDO(); 
        if (!$this->db) {
            error_log("Erreur: connexion à la base de données échouée");
            echo json_encode(['error' => 'Erreur de connexion à la base de données']);
            exit;
        }
    }

    public function Commented($author_id, $publication_id, $content) {
        try {
            $this->db->beginTransaction(); 

            $query = "INSERT INTO comments (author_id, publication_id, content, created_at) 
            VALUES (:author_id, :publication_id, :content, CURRENT_TIMESTAMP)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':author_id', $author_id, PDO::PARAM_INT);
            $stmt->bindParam(':publication_id', $publication_id, PDO::PARAM_INT);
            $stmt->bindParam(':content', $content, PDO::PARAM_STR);
            $stmt->execute();

            $commentId = $this->db->lastInsertId();

            $selectQuery = "SELECT comments.*, users.username, users.pictures FROM comments JOIN users ON comments.author_id = users.id  WHERE comments.id = :comment_id";
            $selectStmt = $this->db->prepare($selectQuery);
            $selectStmt->bindParam(':comment_id', $commentId, PDO::PARAM_INT);
            $selectStmt->execute();
            $newComment = $selectStmt->fetch(PDO::FETCH_ASSOC);

            $this->db->commit();

            echo json_encode([
                'success' => true,
                'message' => 'Commentaire ajouté avec succès',
                'comment' => $newComment,
            ]);
        } catch (Exception $e) {
            $this->db->rollBack();
            http_response_code(500);
            echo json_encode([
                'error' => 'Erreur lors de l\'ajout du commentaire',
                'details' => $e->getMessage()
            ]);
        }
    }
}