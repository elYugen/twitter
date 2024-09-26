<?php

// namespace App\model\CreatePost;
// use PDO; 
// use Exception;

require_once('../function.php');
require_once('../services/ImageUpload.php');
require_once('../services/ExtractHashtag.php');

class CreatePost {
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

    public function Create($content, $author_id) {
        try {
            $this->db->beginTransaction(); 

            $query = "INSERT INTO publications (author_id, content, publishdate) VALUES (:author_id, :content, NOW())";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':author_id', $author_id, PDO::PARAM_INT);
            $stmt->bindParam(':content', $content, PDO::PARAM_STR);

            if (!$stmt->execute()) {
                throw new Exception("Erreur lors de la création de la publication");
            }

            $post_id = $this->db->lastInsertId(); 

            if ($this->image_url) {
                $uploadImage = new ImageUpload($this->db); 
                $uploadImage->Upload($this->image_url, $this->max_file_size, $this->allowed_extensions, $post_id, $author_id);
            }


            $hashtags = $this->extractHashtags($content);
            if (!empty($hashtags)) {
                $extractHashtag = new ExtractHashtag($this->db);
                $extractHashtag->Extracted($hashtags, $post_id);
            }

            $this->db->commit();

            http_response_code(201);
            echo json_encode([
                'success' => true,
                'message' => 'Publication créée avec succès',
                'post_id' => $post_id,
                'image_url' => $this->image_url
            ]);

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Erreur serveur: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Erreur serveur: ' . $e->getMessage()]);
        }
    }


    private function extractHashtags($content) {
        preg_match_all('/#(\w+)/', $content, $matches); 
        return $matches[1]; // Retourne les hashtags sans le symbole #
    }
}
