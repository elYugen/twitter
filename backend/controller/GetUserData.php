<?php
header("Access-Control-Allow-Origin: http://localhost:5173"); 
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

require_once('../function.php');
require_once('../model/GetUserData.php');

$connect = new Connect();

// check si l'id de l'utilisateur est dans l'url
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'ID utilisateur non fourni']);
    exit;
}

$userId = $_GET['id'];

$userinfo = new GetUserData($connect);
$userinfo->UserData($userId);