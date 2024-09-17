<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit();
}

    // Récupère le fichier de connexion à la base de donnée 
    try {
        if(!@require_once('./config/config.php')) {
            throw new Exception("Le fichier config.php n'a pas pu être inclus");
        }
        echo "Connexion à la base de donnée effectué";
    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage() . "<br/>";
    }

    include('session.php');
    include('getAllPosts.php');
?>