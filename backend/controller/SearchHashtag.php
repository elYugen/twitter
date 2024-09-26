<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

require_once('../function.php');
require_once('../model/SearchHashtag.php');

$connect = new Connect();

$searchTerm = isset($_GET['term']) ? $_GET['term'] : '';

$search = new SearchHashtag($connect);
$search->SearchHashtagOnBar($searchTerm);