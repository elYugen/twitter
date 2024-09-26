<?php
require_once('../function.php');
require_once('../model/GetAllPost.php');

header("Access-Control-Allow-Origin: http://localhost:5173"); 
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

$connect = new Connect();
$post = new GetAllPost($connect);
$post->AllPost();

