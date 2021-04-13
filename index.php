<?php
session_start();

require_once "vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

//Pour anticiper le routing, on simplifie la gestion des routes (à améliorer plus tard)
$routing = [
    'home' => ['controller' => 'FrontController', 'action' => 'homeAction'],
    'articlesListing' => ['controller' => 'FrontController', 'action' => 'articlesListingAction'],
    'createArticle' => ['controller' => 'AdminController', 'action' => 'createArticleAction'],
    'detailArticle' => ['controller' => 'FrontController', 'action' => 'detailArticleAction'],
];

//Routing
$page = $_GET['p'] ?? 'home';

//rendu template
if(isset($routing[$page])) {
    $controllerName = 'Blog\\Controller\\'.$routing[$page]['controller'];
    $actionName = $routing[$page]['action'];
    $controller = new $controllerName();
    $controller->$actionName();
}else {
    header('Location: home');
}
