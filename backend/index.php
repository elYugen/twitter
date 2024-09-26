<?php 

namespace App;

// ------------- Gestion des Cors ------------
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');


if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit(0);
}

use App\core\Router;


// -------- Dépendences autoloader ----------
require_once __DIR__ . '/vendor/autoload.php';

use App\controller\UserController;
use App\controller\SessionController;


// --------- Router -----------
include_once "./core/Router.php";
$router = new Router();


// Liste des routes Utilisateur
$router->addRoute('POST', '/user/register', UserController::class, 'UserRegister');
$router->addRoute('POST', '/user/login', UserController::class, 'UserLogin');
$router->addRoute('POST', '/user/logout', UserController::class, 'UserLogout');

// Liste des routes Session
$router->addRoute('GET', '/session', SessionController::class, 'isSession');

// Liste des routes Publications




// $router->addRoute('GET', '/readtest/:testID', function($testID) {
//     echo " Niquez vous => $testID !";
//     exit;
// });



// --------- AutoLoader ---------

// use App\services\ImageUploadService;

// $createPost = new ImageUploadService;

// var_dump($createPost);
