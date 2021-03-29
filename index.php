<?php

require_once "vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

//Pour anticiper le routing, on simplifie la gestion des routes (à améliorer plus tard)
$routing = [
    'home' => ['controller' => 'FrontController', 'action' => 'homeAction'],
    'list' => ['controller' => 'FrontController', 'action' => 'listingAction'],
    'contact' => ['controller' => 'FrontController', 'action' => 'contactAction'],
    'register' => ['controller' => 'FrontController', 'action' => 'registerAction'],
    'connect' => ['controller' => 'FrontController', 'action' => 'connectAction'],
    'detailsArticle' => ['controller' => 'FrontController', 'action' => 'detailsAction'],
    'addArticle' => ['controller' => 'AdminController', 'action' => 'addArticleAction']
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





