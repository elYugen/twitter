<?php

function dbconnect() {
    if (file_exists(__DIR__ . '/.env')) {
        $env = parse_ini_file(__DIR__ . '/.env');
    } else {
        die('.env pas trouvé');
    }


try {
    $info = "mysql:host={$env['DB_HOST']};dbname={$env['DB_NAME']};";
    $pdo = new PDO($info, $env['DB_USER'], $env['DB_PASSWORD'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    return $pdo;

} catch (PDOException $e) {
    die("erreur de connexion : " . $e->getMessage());
}
}

?>