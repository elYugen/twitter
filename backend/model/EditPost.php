<?php

require_once('../function.php');
class EditPost {
    private $db;

    public function __construct($connect) {
        $this->db = $connect->getPDO();
        if (!$this->db) {
            error_log("Erreur: connexion à la base de données échouée");
            echo json_encode(['error' => 'Erreur de connexion à la base de données']);
            exit;
        }
    }

    public function Edited($post_id, $content)
    {
        $query = "UPDATE publications SET content = :content WHERE id = :post_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':content', $content, PDO::PARAM_STR);
        $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    
        try {
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return ['success' => true, 'message' => 'Publication mise à jour avec succès'];
            } else {
                return ['success' => false, 'message' => 'Aucune modification effectuée ou publication non trouvée'];
            }
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour de la publication: " . $e->getMessage());
            return ['error' => 'Erreur lors de la mise à jour de la publication', 'details' => $e->getMessage()];
        }
    }
    
}

?>