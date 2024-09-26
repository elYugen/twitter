<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

require_once('../function.php');
require_once('../model/DeletePost.php');

// Gérer les requêtes OPTIONS (pre-flight)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Vérifier si la requête est une méthode POST
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée']);
    exit;
}


$data = json_decode(file_get_contents("php://input"), true);


if (!isset($data['post_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de publication manquant']);
    exit;
}

$post_id = $data['post_id'];

$connect = new Connect();

$delete = new DeletePost($connect);
$delete->DeletePostOnProfile($post_id);