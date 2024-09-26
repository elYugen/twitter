<?php

namespace App\controller;

use App\model\GetUserData;

require_once('../function.php');
require_once('../model/UserRegister.php');

// Gestion des Cors
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');


if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit(0);
}

class UserController {

    private $users = [];

    public function UserLogin() {
        // Démarrage de la session
        session_start();

    }

    public function UserRegister() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);

            if (isset($data['username']) && isset($data['password'])) {
                foreach ($this->users as $user) {
                    if ($user['username'] === $data['username']) {
                        http_response_code(409); 
                        echo json_encode(['error' => 'Nom d\'utilisateur déjà pris.']);
                        return;
                    }
                }
                $this->users[] = [
                    'username' => $data['username'],
                    'password' => password_hash($data['password'], PASSWORD_DEFAULT)
                ];
                http_response_code(201); 
                header('Content-Type: application/json'); 
                echo json_encode(['success' => true, 'message' => 'Inscription réussie.']);
            } else {
                http_response_code(400);
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Données manquantes.']);
            }
        } else {
            http_response_code(405); 
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Méthode non autorisée.']);
        }
    }

    public function UserLogout() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // détruire la session
            session_unset();
            session_destroy();
            
            error_log("UserLogout a été appelé."); 
            echo json_encode(['success' => true, 'message' => 'Déconnexion réussie']);
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Méthode non autorisée']);
        }
    }

    public function GetUserData($connect) {
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'ID utilisateur non fourni']);
            exit;
        }
        
        $userId = $_GET['id'];
        
        $userinfo = new GetUserData($connect);
        $userinfo->UserData($userId);
    }
}