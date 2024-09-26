<?php

namespace App\core;
use Exception;

    // fonction pour ajouter une route
    // $route = chemin de la route (par exemple "/" pour la page d'accueil)
    // $controller = nom du controller
    // $target fonction du controller
    
class Router {
    // liste des route
    public $routes = [];

    public function addRoute(string $route, string $controller, string $target) {
        $routeRegex = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $route);
        $this->routes[$routeRegex] = ['controller' => $controller, 'action' => $target];
    }


    public function matchRoute(string $uri)
    {
        foreach ($this->routes as $routePattern => $routeConfig) {
            // verifie si l'url correspond au pattern
            if (preg_match("#$routePattern#", $uri, $matches)) {
                // supprime le 1er élément du match pour garder seulement les parametres
                array_shift($matches);
                // fusionne les valeur et paramètre du tableau $routes
                $routeParams = array_combine(array_keys($matches), array_values($matches));
                // récupère le controller et la target
                $controllerClass = $routeConfig['controller'];
                $target = $routeConfig['action'];
                // instancier le controller
                $controller = new $controllerClass();
                // appel target et l'envoie dans la route
                $controller->$target($routeParams);

                return;
            }
        }
        throw new Exception("Aucune route trouvé");
    }
}