<?php

namespace App\services;

use Exception;
use App\vendor\Dbconnect;

class ImageUpload {

    private $db;

    public function __construct() {
        $this->db = Dbconnect::getInstance()->getConnection();
        if (!$this->db) {
            error_log("Erreur: connexion à la base de données échouée");
            echo json_encode(['error' => 'Erreur de connexion à la base de données']);
            exit;
        }
    }

    public function Upload($image_url, $max_file_size, $allowed_extensions, $post_id, $author_id) {
        if (isset($_FILES['image'])) {
            if ($_FILES['image']['size'] > $max_file_size) {
                throw new Exception("Le fichier dépasse les 10 Mo");
            }
    
            $image_info = pathinfo($_FILES['image']['name']);
            $image_extension = strtolower($image_info['extension']);
    
            if (!in_array($image_extension, $allowed_extensions)) {
                throw new Exception("Fichier accepté : png, jpg, jpeg, webp, gif");
            }
    
            if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $image_tmp = $_FILES['image']['tmp_name'];
                $image_name = uniqid() . '.' . $image_extension;
                $upload_path = $_SERVER['DOCUMENT_ROOT'] . '/twitter/public/upload/' . $image_name;
    
                if (move_uploaded_file($image_tmp, $upload_path)) {
                    $image_url = 'http://localhost:5173/public/upload/' . $image_name;
    
                    // Insertion de l'image dans la base de données
                    $image_query = "INSERT INTO publication_image (publication_id, image, uploader_id) 
                                    VALUES (:publication_id, :image, :uploader_id)";
                    $image_stmt = $this->db->prepare($image_query);
                    $image_stmt->bindParam(':publication_id', $post_id);
                    $image_stmt->bindParam(':image', $image_url);
                    $image_stmt->bindParam(':uploader_id', $author_id);
                    $image_stmt->execute();
                    
                    $image_id = $this->db->lastInsertId();
    
                    // Mise à jour de la publication avec l'image
                    $update_query = "UPDATE publications SET image_id = :image_id WHERE id = :post_id";
                    $update_stmt = $this->db->prepare($update_query);
                    $update_stmt->bindParam(':image_id', $image_id);
                    $update_stmt->bindParam(':post_id', $post_id);
                    $update_stmt->execute();
                }
            }
        }
    }
}