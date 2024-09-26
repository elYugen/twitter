<?php
header("Access-Control-Allow-Origin: http://localhost:5173"); 
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

require_once('../function.php');
require_once('../model/GetAllHashtag.php');

$connect = new Connect();
$hashtag = new getAllHashtag($connect);
$hashtag->getHashtag();
