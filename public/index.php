<?php

require "../bootstrap.php";
use Src\Controllers\PostController;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

if ($uri[1] !== 'api') {
    header("HTTP/1.1 404 Not Found");
    exit();
}

if (!isset($uri[2])) {
   header("HTTP/1.1 404 Not Found");
   exit();
}
$requestMethodType = $_SERVER["REQUEST_METHOD"];
$requestMethod = $uri[2];

$controller = new PostController($requestMethod, $requestMethodType);
$controller->processRequest();





