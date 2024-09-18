<?php 
require_once('function.php');

header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

if (isset($data['login'])) {
    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';
    $email = $data['email'] ?? '';

    if (empty($username) || empty($password) || empty($email)) {
        http_response_code(400);
        echo json_encode(['error' => 'champs manquants']);
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    try {
        $dbh = dbconnect();
        $query = "INSERT INTO users (username, password, email, created_at) VALUES (:username, :password, :email, NOW())";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':email', $email);

        if ($stmt->execute()) {
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;

            http_response_code(201);
            echo json_encode(['success' => true, 'message' => 'utilisateur créé !']);
        } else {
            throw new Exception("erreur lors de l'insertion");
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'erreur serveur: ' . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Données invalides']);
}
?>