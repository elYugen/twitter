<?php
require_once('../model/UserLogin.php'); 
header('Content-Type: application/json');

session_start();

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['username']) && isset($data['password'])) {
    $username = $data['username'];
    $password = $data['password'];

    if (!empty($username) && !empty($password)) {
        $userModel = new UserLogin($connect);

        $user = $userModel->findUserByUsername($username);

        if ($user) {
            if ($userModel->verifyPassword($password, $user['password'])) {

                $_SESSION['id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['pictures'] = $user['pictures'];
                $_SESSION['created_at'] = $user['created_at'];


                echo json_encode([
                    'success' => true,
                    'message' => 'Connexion réussie',
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Mot de passe incorrect'
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Utilisateur non trouvé'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Champs manquants'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Requête invalide'
    ]);
}
