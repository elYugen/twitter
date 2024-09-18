<?php
require_once('function.php');

header("Access-Control-Allow-Origin: http://localhost:5173"); 
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// Vérifier si l'ID de la publication est fourni dans l'URL
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de publication non fourni']);
    exit;
}

$postId = $_GET['id'];

$db = dbconnect();

// Sélectionner une publication spécifique avec les informations de l'auteur
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
WHERE publications.id = :id";

$stmt = $db->prepare($query);
$stmt->bindParam(':id', $postId, PDO::PARAM_INT);
$stmt->execute();

$post = $stmt->fetch(PDO::FETCH_ASSOC);

if ($post) {
    // Récupérer les commentaires pour cette publication
    $commentQuery = "SELECT 
        comments.id,
        comments.content,
        comments.created_at,
        users.id AS user_id,
        users.username,
        users.pictures
    FROM comments
    JOIN users ON comments.author_id = users.id
    WHERE comments.publication_id = :post_id
    ORDER BY comments.created_at DESC";

    $commentStmt = $db->prepare($commentQuery);
    $commentStmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
    $commentStmt->execute();

    $comments = $commentStmt->fetchAll(PDO::FETCH_ASSOC);

    // Ajouter les commentaires à la publication
    $post['comments'] = $comments;

    echo json_encode($post);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Publication non trouvée']);
}