<?php
require_once('function.php');

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

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

$db = dbconnect();

$query = "DELETE FROM publications WHERE id = :post_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
try {
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Publication supprimée avec succès']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Aucune publication trouvée avec cet ID']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur lors de la suppression de la publication', 'details' => $e->getMessage()]);
}