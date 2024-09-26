<?php

/* MODIFICATION :
*  Ajout pattern dans la fonction addRoute et supprimer dans matchRoute
*  Changement de array_filter par array_shift
*  Ajout de array_combine pour fusionner les paramètres et les valeurs
*/
namespace App\core;

use Exception;

class Router {
    
    // dossier de départ du router
    protected $basePath = '';

    public function setBasePath($basePath) {
        $this->basePath = rtrim($basePath, '/');
    }


    // liste des routes de l'app contenu dans un tableau
    protected $routes = []; 

    // fonction pour ajouter une route
    public function addRoute(string $method, string $url, string $targetClass, string $targetMethod) {
        //créer un motif regex pour la route
        $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $url);
        //stock la regex avec le controller et l'action
        $this->routes[$method][$pattern] = [$targetClass, $targetMethod];
    }

    // fonction pour faire correspondre les requetes avec les routes correspondante dans l'url
    public function matchRoute() {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = $_SERVER['REQUEST_URI'];

        // echo "Methode demandé : $method"; // débogage
        // echo "<br>URL demandé : $url"; // débogage

        // supprime le chemin de base de l'application de l'url
        if (!empty($this->basePath) && strpos($url, $this->basePath) === 0) {
            $url = substr($url, strlen($this->basePath));
        }
        // echo "<br>URL demandée : " . $url . "<br>";  // débogage

        // si la méthode de la requete correspond à une méthode de route
        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $routeUrl => $target) {
                // verifie si l'url correspond au regex
                if (preg_match('#^' . $routeUrl . '$#', $url, $matches)) {
                    // supprime le 1er élément de la correspondance car c'est l'url
                    array_shift($matches);

                    //fusionne les paramètres avec leurs valeurs
                    $params = array_combine(array_keys($matches), array_values($matches));
                    
                    list($targetClass, $targetMethod) = $target; // appel de la méthode de la classe cible
                    $controller = new $targetClass(); // instancier la classe cible
                    
                    call_user_func_array([$controller, $targetMethod], $params); // appel la méthode
                    return;
                }
            }
        }
        //retourne une erreur si la route n'existe pas
        throw new Exception('Route non trouvé');
    }

}