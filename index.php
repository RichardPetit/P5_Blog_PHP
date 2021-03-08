<?php

use Blog\Controller\FrontController;

require_once "vendor/autoload.php";

$controller = new FrontController();
$controller->homeAction();

