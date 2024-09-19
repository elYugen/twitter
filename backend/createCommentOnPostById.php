<?php
require_once('function.php');

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['author_id']) || !isset($data['publication_id']) || !isset($data['content'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Données manquantes']);
    exit;
}

$author_id = $data['author_id'];
$publication_id = $data['publication_id'];
$content = $data['content'];


$db = dbconnect();


$db->beginTransaction();

try {

    $query = "INSERT INTO comments (author_id, publication_id, content, created_at) 
              VALUES (:author_id, :publication_id, :content, CURRENT_TIMESTAMP)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':author_id', $author_id, PDO::PARAM_INT);
    $stmt->bindParam(':publication_id', $publication_id, PDO::PARAM_INT);
    $stmt->bindParam(':content', $content, PDO::PARAM_STR);
    $stmt->execute();

    $commentId = $db->lastInsertId();

    // Gestion de l'upload d'image
    $image_url = null;
    $max_file_size = 10 * 1024 * 1024; // Taille max : 10 Mo
    $allowed_extensions = ['png', 'jpg', 'jpeg', 'webp', 'gif']; // extension

    if (isset($_FILES['image'])) {
        // verif taille fichier
        if ($_FILES['image']['size'] > $max_file_size) {
            throw new Exception("Le fichier dépasse les 10 mo");
        }

        // verif extension
        $image_info = pathinfo($_FILES['image']['name']);
        $image_extension = strtolower($image_info['extension']);

        if (!in_array($image_extension, $allowed_extensions)) {
            throw new Exception("Seuls les fichiers PNG, JPG, JPEG, WEBP et GIF sont autorisés.");
        }

        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image_tmp = $_FILES['image']['tmp_name'];
            $image_name = uniqid() . '.' . $image_extension;
            $upload_path = $_SERVER['DOCUMENT_ROOT'] . '/twitter/public/upload/' . $image_name;

            if (move_uploaded_file($image_tmp, $upload_path)) {
                $image_url = 'http://localhost:5173/public/upload/' . $image_name;

                // Insertion de l'image dans la base de données
                $image_query = "INSERT INTO comment_images (comment_id, image_url) 
                                VALUES (:comment_id, :image_url)";
                $image_stmt = $db->prepare($image_query);
                $image_stmt->bindParam(':comment_id', $commentId, PDO::PARAM_INT);
                $image_stmt->bindParam(':image_url', $image_url, PDO::PARAM_STR);
                $image_stmt->execute();
            } else {
                throw new Exception("Erreur lors du déplacement de l'image téléchargée.");
            }
        } else {
            throw new Exception("Erreur lors de l'upload de l'image.");
        }
    }

    $selectQuery = "SELECT comments.*, users.username, users.pictures 
                    FROM comments 
                    JOIN users ON comments.author_id = users.id 
                    WHERE comments.id = :comment_id";
    $selectStmt = $db->prepare($selectQuery);
    $selectStmt->bindParam(':comment_id', $commentId, PDO::PARAM_INT);
    $selectStmt->execute();
    $newComment = $selectStmt->fetch(PDO::FETCH_ASSOC);

    $db->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Commentaire ajouté avec succès',
        'comment' => $newComment,
        'image_url' => $image_url // aperçu de l'image
    ]);

} catch (Exception $e) {
    // rollback en cas d'erreur
    $db->rollBack();
    http_response_code(500);
    echo json_encode([
        'error' => 'Erreur lors de l\'ajout du commentaire',
        'details' => $e->getMessage()
    ]);
}
