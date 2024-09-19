<?php
require_once('function.php');

session_start(); 
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

error_log("Contenu de la session : " . print_r($_SESSION, true));

if (!isset($_SESSION['id'])) {
    http_response_code(401);
    error_log("Erreur: utilisateur non connecté");
    echo json_encode(['error' => 'Utilisateur non connecté']);
    exit;
}

$content = isset($_POST['content']) ? $_POST['content'] : null;

if (!$content) {
    http_response_code(400);
    error_log("Erreur: contenu manquant");
    echo json_encode(['error' => 'Contenu manquant']);
    exit;
}

$author_id = $_SESSION['id'];

// Fonction pour extraire les hashtags
function extractHashtags($content) {
    preg_match_all('/#(\w+)/', $content, $matches);
    return $matches[1]; // Retourne uniquement les hashtags sans le symbole #
}

try {
    $dbh = dbconnect();
    if (!$dbh) {
        error_log("Erreur: connexion à la base de données échouée");
        echo json_encode(['error' => 'Erreur de connexion à la base de données']);
        exit;
    }

    $dbh->beginTransaction();

    // Insertion de la publication
    $query = "INSERT INTO publications (author_id, content, publishdate) 
              VALUES (:author_id, :content, NOW())";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':author_id', $author_id);
    $stmt->bindParam(':content', $content);

    if (!$stmt->execute()) {
        throw new Exception("Erreur lors de la création de la publication");
    }

    $post_id = $dbh->lastInsertId();

    // Gestion de l'upload d'image (déjà dans votre code)
    $image_url = null;
    $max_file_size = 10 * 1024 * 1024;
    $allowed_extensions = ['png', 'jpg', 'jpeg', 'webp', 'gif'];

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
                $image_stmt = $dbh->prepare($image_query);
                $image_stmt->bindParam(':publication_id', $post_id);
                $image_stmt->bindParam(':image', $image_url);
                $image_stmt->bindParam(':uploader_id', $author_id);
                $image_stmt->execute();
                
                $image_id = $dbh->lastInsertId();

                // Mise à jour de la publication avec l'image
                $update_query = "UPDATE publications SET image_id = :image_id WHERE id = :post_id";
                $update_stmt = $dbh->prepare($update_query);
                $update_stmt->bindParam(':image_id', $image_id);
                $update_stmt->bindParam(':post_id', $post_id);
                $update_stmt->execute();
            }
        }
    }

    // Extraction des hashtags
    $hashtags = extractHashtags($content);

    // Insertion des hashtags dans la base de données
    foreach ($hashtags as $tag) {
        // Vérification si le hashtag existe déjà
        $hashtag_query = "SELECT id FROM hashtags WHERE tag = :tag";
        $hashtag_stmt = $dbh->prepare($hashtag_query);
        $hashtag_stmt->bindParam(':tag', $tag);
        $hashtag_stmt->execute();
        $hashtag_id = $hashtag_stmt->fetchColumn();

        // Si le hashtag n'existe pas, l'ajouter
        if (!$hashtag_id) {
            $insert_hashtag_query = "INSERT INTO hashtags (tag) VALUES (:tag)";
            $insert_hashtag_stmt = $dbh->prepare($insert_hashtag_query);
            $insert_hashtag_stmt->bindParam(':tag', $tag);
            $insert_hashtag_stmt->execute();
            $hashtag_id = $dbh->lastInsertId();
        }

        // Lier le hashtag à la publication
        $link_query = "INSERT INTO publication_hashtags (publication_id, hashtag_id) 
                       VALUES (:publication_id, :hashtag_id)";
        $link_stmt = $dbh->prepare($link_query);
        $link_stmt->bindParam(':publication_id', $post_id);
        $link_stmt->bindParam(':hashtag_id', $hashtag_id);
        $link_stmt->execute();
    }

    $dbh->commit();

    http_response_code(201);
    echo json_encode([
        'success' => true, 
        'message' => 'Publication créée avec succès',
        'post_id' => $post_id,
        'image_url' => $image_url
    ]);

} catch (Exception $e) {
    $dbh->rollBack();
    error_log("Erreur serveur: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Erreur serveur: ' . $e->getMessage()]);
}
