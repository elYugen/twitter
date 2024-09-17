<?php
require_once('function.php');

$db = dbconnect();
$query = "SELECT * FROM publications";
$stmt = $db->prepare($query);
$stmt ->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
var_dump($posts);
return $posts;