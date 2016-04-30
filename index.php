<?php
include 'autoload.php';

$action = filter_input(INPUT_GET, 'route');

$controller = Controller::getInstance();

$controller->prepare($action);

$controller->render();