<?php
require_once('function.php');

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

$hashtag = isset($_GET['hashtag']) ? $_GET['hashtag'] : null;

if (!$hashtag) {
    http_response_code(400);
    echo json_encode(['error' => 'Hashtag manquant']);
    exit;
}

try {
    $dbh = dbconnect();

    $query = "SELECT publications.*, users.username, users.pictures
FROM publications
JOIN publication_hashtags ON publications.id = publication_hashtags.publication_id
JOIN hashtags ON hashtags.id = publication_hashtags.hashtag_id
JOIN users ON publications.author_id = users.id
WHERE hashtags.tag = :hashtag";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':hashtag', $hashtag);
    $stmt->execute();

    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode(['posts' => $posts]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur serveur: ' . $e->getMessage()]);
}
