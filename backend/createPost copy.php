<?php
require_once('function.php');

session_start(); 
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

// Gérer les requêtes OPTIONS pour le CORS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// log si la session est active
error_log("Contenu de la session : " . print_r($_SESSION, true));

// verif si l'utilisateur est co
if (!isset($_SESSION['id'])) {
    http_response_code(401);
    error_log("Erreur: utilisateur non connecté");
    echo json_encode(['error' => 'Utilisateur non connecté']);
    exit;
}

// recup les data envoyés via post
$data = json_decode(file_get_contents("php://input"), true);

// verif le contenue envoyé
error_log("Données reçues : " . print_r($data, true));

// verif si le textarea contient des choses
if (!isset($data['content'])) {
    http_response_code(400);
    error_log("Erreur: contenu manquant");
    echo json_encode(['error' => 'Contenu manquant']);
    exit;
}

$author_id = $_SESSION['id'];
$content = $data['content'];
$image_id = $data['image_id'] ?? null;

try {
    $dbh = dbconnect();
    if (!$dbh) {
        error_log("Erreur: connexion à la base de données échouée");
        echo json_encode(['error' => 'Erreur de connexion à la base de données']);
        exit;
    }
    $query = "INSERT INTO publications (author_id, content, publishdate, image_id) 
              VALUES (:author_id, :content, NOW(), :image_id)";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':author_id', $author_id);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':image_id', $image_id);

    if ($stmt->execute()) {
        $post_id = $dbh->lastInsertId();
        http_response_code(201);
        echo json_encode([
            'success' => true, 
            'message' => 'Publication créée avec succès',
            'post_id' => $post_id
        ]);
    } else {
        throw new Exception("Erreur lors de la création de la publication");
    }
} catch (Exception $e) {
    error_log("Erreur serveur: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Erreur serveur: ' . $e->getMessage()]);
}
