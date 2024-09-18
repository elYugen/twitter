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

$data = json_decode(file_get_contents("php://input"), true);
error_log("Données reçues : " . print_r($data, true));

if (!isset($data['content'])) {
    http_response_code(400);
    error_log("Erreur: contenu manquant");
    echo json_encode(['error' => 'Contenu manquant']);
    exit;
}

$author_id = $_SESSION['id'];
$content = $data['content'];

try {
    $dbh = dbconnect();
    if (!$dbh) {
        error_log("Erreur: connexion à la base de données échouée");
        echo json_encode(['error' => 'Erreur de connexion à la base de données']);
        exit;
    }

    $dbh->beginTransaction();

    $query = "INSERT INTO publications (author_id, content, publishdate) 
              VALUES (:author_id, :content, NOW())";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':author_id', $author_id);
    $stmt->bindParam(':content', $content);

    if (!$stmt->execute()) {
        throw new Exception("Erreur lors de la création de la publication");
    }

    $post_id = $dbh->lastInsertId();

    // Traiter l'image si elle est présente
    if (isset($data['image']) && !empty($data['image'])) {
        $image_data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data['image']));
        $image_name = uniqid() . '.png';
        $upload_path = $_SERVER['DOCUMENT_ROOT'] . '/public/upload/' . $image_name;
        
        if (file_put_contents($upload_path, $image_data)) {
            $image_url = 'http://localhost:5173/public/upload/' . $image_name;

            $image_query = "INSERT INTO publication_image (publication_id, image, uploader_id) 
                            VALUES (:publication_id, :image, :uploader_id)";
            $image_stmt = $dbh->prepare($image_query);
            $image_stmt->bindParam(':publication_id', $post_id);
            $image_stmt->bindParam(':image', $image_url);
            $image_stmt->bindParam(':uploader_id', $author_id);

            if (!$image_stmt->execute()) {
                throw new Exception("Erreur lors de l'enregistrement de l'image");
            }

            $image_id = $dbh->lastInsertId();

            $update_query = "UPDATE publications SET image_id = :image_id WHERE id = :post_id";
            $update_stmt = $dbh->prepare($update_query);
            $update_stmt->bindParam(':image_id', $image_id);
            $update_stmt->bindParam(':post_id', $post_id);

            if (!$update_stmt->execute()) {
                throw new Exception("Erreur lors de la mise à jour de la publication avec l'ID de l'image");
            }
        } else {
            throw new Exception("Erreur lors de l'enregistrement de l'image sur le serveur");
        }
    }

    $dbh->commit();

    http_response_code(201);
    echo json_encode([
        'success' => true, 
        'message' => 'Publication créée avec succès',
        'post_id' => $post_id
    ]);

} catch (Exception $e) {
    $dbh->rollBack();
    error_log("Erreur serveur: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Erreur serveur: ' . $e->getMessage()]);
}