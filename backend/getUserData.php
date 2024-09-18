<?php
require_once('function.php');

header("Access-Control-Allow-Origin: http://localhost:5173"); 
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// check si l'id de l'utilisateur est dans l'url
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'ID utilisateur non fourni']);
    exit;
}

$userId = $_GET['id'];

$db = dbconnect();

// selectionne un utilisateur en particulier
$query = "SELECT id, username, email, pictures, created_at FROM users WHERE id = :id";

$stmt = $db->prepare($query);
$stmt->bindParam(':id', $userId, PDO::PARAM_INT);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    echo json_encode($user);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Utilisateur non trouvé']);
}