<?php
require_once('function.php');

header("Access-Control-Allow-Origin: http://localhost:5173"); 
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'id non trouvé']);
    exit;
}

$userId = $_GET['id'];

$db = dbconnect();

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
WHERE publications.author_id = :id
ORDER BY publications.publishdate DESC";

$stmt = $db->prepare($query);
$stmt->bindParam(':id', $userId, PDO::PARAM_INT);
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($posts);