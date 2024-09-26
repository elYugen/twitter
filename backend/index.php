<?php 

namespace App;

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

require_once __DIR__ . '/vendor/autoloadALaMain.php';

// -------- Dépendences autoloader ----------
use App\controller\UserController;
use App\controller\SessionController;

use App\core\Router;


// --------- Router -----------
include_once "./core/Router.php";
$router = new Router();


// Liste des routes Utilisateur
$router->addRoute('/user/{id}', UserController::class, 'GetUserData');
$router->addRoute('/user/logout', UserController::class, 'UserLogout');
$router->addRoute('/user/login', UserController::class, 'UserLogin');
$router->addRoute('/user/register', UserController::class, 'UserRegister');

// Liste des routes Session
$router->addRoute('/session', SessionController::class, 'isSession');

// Liste des routes Publications




// $router->addRoute('GET', '/readtest/:testID', function($testID) {
//     echo " Niquez vous => $testID !";
//     exit;
// });



// --------- AutoLoader ---------

// use App\services\ImageUpload;

// $createPost = new ImageUpload;

// var_dump($createPost);
