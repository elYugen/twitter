<?php 

// si $class est instancé dans le namespass "App\" alors trouve le bon fichier dans le dossier de base
spl_autoload_register( function($class) {

    // le préfix des namespace que l'on utilise pour autoload les bonnes classes et ignorer les autres
    $prefix = 'App\\';

    // dossier de base du projet
    $base_dir = __DIR__.'/';

    /*
    * compare les premières lettre $len du nom de la class contre le préfix
    * si elles ne sont pas identique alors, passe a l'autre autoload de la liste 
    */
    // longueur du préfix
    $len = strlen($prefix);

    // si le premier caractère de $len ne correspond pas
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // récupère le nom de la class après que le préfix a été appliqué
    $class_name = str_replace($prefix, '', $class);

    /* 
    * remplace les \ du namespace avec les séparateur classique "/"
    * prépare le dossier de base
    * rajoute le .php
    */ 
    $file = $base_dir . str_replace('\\', '/', $class_name) . 'php';

    // require si le fichier existe
    if (file_exists($file)) {
        require $file;
    }
});