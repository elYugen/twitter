<?php

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

require_once('../function.php');
require_once('../model/CreateComment.php');

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



$connect = new Connect();
$create = new CreateComment($connect);
$create->Commented($author_id, $publication_id, $content);