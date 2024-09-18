<?php
require_once('function.php');

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['post_id']) || !isset($data['content'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Données manquantes']);
    exit;
}

$post_id = $data['post_id'];
$content = $data['content'];

$db = dbconnect();

$query = "UPDATE publications SET content = :content WHERE id = :post_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':content', $content, PDO::PARAM_STR);
$stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);

try {
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Publication mise à jour avec succès']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Aucune modification effectuée ou publication non trouvée']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur lors de la mise à jour de la publication', 'details' => $e->getMessage()]);
}