<?php

require_once('../function.php');

class DeletePost {

    private $db;

    public function __construct($connect) {
        $this->db = $connect->getPDO();
        if (!$this->db) {
            error_log("Erreur: connexion à la base de données échouée");
            echo json_encode(['error' => 'Erreur de connexion à la base de données']);
            exit;
        }
    }

    public function DeletePostOnProfile($post_id)
    {
        try {
            $this->db->beginTransaction();

            // Suppr les commentaires lié au post
            $query = "DELETE FROM comments WHERE publication_id = :post_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
            $stmt->execute();

            // Suppr les hashtag lié au post
            $query = "DELETE FROM publication_hashtags WHERE publication_id = :post_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
            $stmt->execute();

            // Suppr l'image lié au post
            $query = "DELETE FROM publication_image WHERE publication_id = :post_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
            $stmt->execute();

            // Suppr la publication
            $query = "DELETE FROM publications WHERE id = :post_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
            $stmt->execute();

            $this->db->commit();

            if ($stmt->rowCount() > 0) {
                echo json_encode(['success' => true, 'message' => 'Publication supprimée avec succès']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Aucune publication trouvée avec cet ID']);
            }
        } catch (PDOException $e) {
            $this->db->rollBack();
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de la suppression de la publication', 'details' => $e->getMessage()]);
        }
    }
}