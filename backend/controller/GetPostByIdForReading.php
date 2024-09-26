<?php
header("Access-Control-Allow-Origin: http://localhost:5173"); 
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

require_once('../function.php');
require_once('../model/GetPostByIdForReading.php');

$connect = new Connect();

// Vérifier si l'ID de la publication est fourni dans l'URL
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de publication non fourni']);
    exit;
}

$postId = $_GET['id'];



// select une publication spécifique avec les infos de l'auteur
$getPosts = new GetPostByIdForReading($connect);
$getPosts->GetPost($postId);

?>