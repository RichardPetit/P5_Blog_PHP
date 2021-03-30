<?php

require_once "vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

//Pour anticiper le routing, on simplifie la gestion des routes (à améliorer plus tard)
$routing = [
    'home' => ['controller' => 'Blog\Controller\FrontController', 'action' => 'homeAction'],
    'list' => ['controller' => 'Blog\Controller\FrontController', 'action' => 'listingAction'],
    'contact' => ['controller' => 'Blog\Controller\FrontController', 'action' => 'contactAction'],
    'register' => ['controller' => 'Blog\Controller\FrontController', 'action' => 'registerAction'],
    'connect' => ['controller' => 'Blog\Controller\FrontController', 'action' => 'connectAction'],
    'detailsArticle' => ['controller' => 'Blog\Controller\FrontController', 'action' => 'detailsAction'],
    'addArticle' => ['controller' => 'Blog\Controller\AdminController', 'action' => 'addArticleAction']
];

//Routing
$page = $_GET['p'] ?? 'home';

//rendu template
if(isset($routing[$page])) {
    $controllerName = $routing[$page]['controller'];
    $actionName = $routing[$page]['action'];
    $controller = new $controllerName();
    $controller->$actionName();
}else {
    header('Location: home');
}





