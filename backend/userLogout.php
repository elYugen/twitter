<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // détruire la session
    session_unset();
    session_destroy();
    
    echo json_encode(['success' => true, 'message' => 'Déconnexion réussie']);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée']);
}