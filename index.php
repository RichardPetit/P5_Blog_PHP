<?php

use Blog\Controller\FrontController;

require_once "vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

//Routing
$page = 'home';
if (isset($_GET['p'])){
    $page = $_GET['p'];
}

//rendu template

if ( $page === 'home')
{
    $controller = new FrontController();
    $controller->homeAction();
} elseif ( $page ==='list'){
    $controller = new FrontController();
    $controller->listingAction();
}elseif ( $page ==='contact'){
    $controller = new FrontController();
    $controller->contactAction();
}elseif ( $page === 'register'){
    $controller = new FrontController();
    $controller->registerAction();
}elseif ( $page === 'connect'){
    $controller = new FrontController();
    $controller->connectAction();
}elseif ( $page ==='newArticle'){
    $controller = new FrontController();
    $controller->newArticleAction();
}else {
    header('Location: home');
}






