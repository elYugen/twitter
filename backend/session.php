<?php
require_once('function.php');
session_start();

header("Access-Control-Allow-Origin: http://localhost:5173"); 
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// Renvoyer les informations de session
if (isset($_SESSION["username"])) {
    $response = [
        'username' => $_SESSION["username"],
        'email' => $_SESSION["email"],
        'pictures' => $_SESSION["pictures"]
    ];
} else {
    // Si aucune session n'est active
    $response = [
        'error' => 'Aucune session active'
    ];
}

echo json_encode($response);
