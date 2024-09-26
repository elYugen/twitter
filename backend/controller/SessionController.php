<?php

namespace App\controller;
// require_once('../function.php');

// Gestion des Cors
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

class SessionController
{

    public function isSession()
    {
        error_log(print_r($_SESSION, true));


        session_start();

        // Si la session existe
        if (isset($_SESSION["id"])) {
            $response = [
                'id' => $_SESSION["id"],
                'username' => $_SESSION["username"],
                'email' => $_SESSION["email"],
                'pictures' => $_SESSION["pictures"],
                'created_at' => $_SESSION["created_at"]

            ];
        } else {
            // Aucune session n'est active
            $response = [
                'error' => 'Aucune session active'
            ];
        }

        echo json_encode($response);
    }
}
