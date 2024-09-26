<?php
session_start(); 
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

require_once('../function.php');
require_once('../model/CreatePost.php');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

error_log("Contenu de la session : " . print_r($_SESSION, true));

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    http_response_code(401);
    error_log("Erreur: utilisateur non connecté");
    echo json_encode(['error' => 'Utilisateur non connecté']);
    exit;
}

// recup le contenu
$content = isset($_POST['content']) ? $_POST['content'] : null;

if (!$content) {
    http_response_code(400);
    error_log("Erreur: contenu manquant");
    echo json_encode(['error' => 'Contenu manquant']);
    exit;
}

$author_id = $_SESSION['id'];


$image_url = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
    $image_url = $_FILES['image']['tmp_name']; 
}

$connect = new Connect();
$create = new CreatePost($connect);

$create->image_url = $image_url;  // Définir l'URL de l'image (facultatif)
$create->Create($content, $author_id);
