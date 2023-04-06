<?php

require_once '../Router/Router.php';


$request = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];
$router = new Router($request, $method);
$router->route();


