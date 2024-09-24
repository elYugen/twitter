<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

require_once('function.php');

$db = dbconnect();

$searchTerm = isset($_GET['term']) ? $_GET['term'] : '';

$query = "SELECT * FROM hashtags WHERE tag LIKE :term ORDER BY tag ASC";
$stmt = $db->prepare($query);
$stmt->bindValue(':term', "%$searchTerm%", PDO::PARAM_STR);
$stmt->execute();

$hashtags = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $hashtags[] = [
        'id' => $row['id'],
        'tag' => $row['tag']
    ];
}

echo json_encode($hashtags);