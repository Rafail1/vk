<?php
include 'autoload.php';

$route = filter_input(INPUT_GET, 'route');
$routteAsArray = explode("/", $route);
if(!$routteAsArray[0]) {
    $routteAsArray[0] = 'index';
}
$controllerName = $routteAsArray[0]."Controller";
$controllerAction = $routteAsArray[1];



$controller = new $controllerName();

$controller->prepare($controllerAction);

$controller->render();
