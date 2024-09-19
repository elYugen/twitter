<?php
require_once('function.php');

header("Access-Control-Allow-Origin: http://localhost:5173"); 
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

$db = dbconnect();

$query = "SELECT * from hashtags";
$stmt = $db->prepare($query);
$stmt->execute();
$categ = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($categ);
