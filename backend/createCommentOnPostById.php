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


$query = "INSERT INTO comments (author_id, publication_id, content, created_at) 
          VALUES (:author_id, :publication_id, :content, CURRENT_TIMESTAMP)";
$stmt = $db->prepare($query);
$stmt->bindParam(':author_id', $author_id, PDO::PARAM_INT);
$stmt->bindParam(':publication_id', $publication_id, PDO::PARAM_INT);
$stmt->bindParam(':content', $content, PDO::PARAM_STR);


try {
    $stmt->execute();
    $commentId = $db->lastInsertId();

    $selectQuery = "SELECT comments.*, users.username, users.pictures 
                    FROM comments 
                    JOIN users ON comments.author_id = users.id 
                    WHERE comments.id = :comment_id";
    $selectStmt = $db->prepare($selectQuery);
    $selectStmt->bindParam(':comment_id', $commentId, PDO::PARAM_INT);
    $selectStmt->execute();
    $newComment = $selectStmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'message' => 'Commentaire ajouté avec succès',
        'comment' => $newComment
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Erreur lors de l\'ajout du commentaire',
        'details' => $e->getMessage()
    ]);
}