<?php

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

require_once('../function.php');
require_once('../model/GetPostsByHashtag.php');

$hashtag = isset($_GET['hashtag']) ? $_GET['hashtag'] : null;

if (!$hashtag) {
    http_response_code(400);
    echo json_encode(['error' => 'Hashtag manquant']);
    exit;
}

$getpost = new GetPostsByHashtag($connect);
$getpost->GetPosts($hashtag);
?>
