<?php
require_once('function.php');

header("Access-Control-Allow-Origin: http://localhost:5173"); 
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

$db = dbconnect();

$query = "SELECT publications.id, publications.author_id, publications.content, publications.publishdate, publications.image_id, users.username, users.pictures
FROM publications
JOIN users ON publications.author_id = users.id";


$stmt = $db->prepare($query);
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($posts);
