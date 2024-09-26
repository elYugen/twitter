<?php
header("Access-Control-Allow-Origin: http://localhost:5173"); 
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

require_once('../function.php');
require_once('../model/GetAllPostsFromUser.php');

$connect = new Connect();

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'id non trouvé']);
    exit;
}

$userId = $_GET['id'];

$allposts = new GetAllPostsFromUser($connect);
$allposts->GetPostFromUser($userId);