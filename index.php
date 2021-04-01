<?php
//
//require_once "vendor/autoload.php";
//
//$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
//$dotenv->load();
//
////Pour anticiper le routing, on simplifie la gestion des routes (à améliorer plus tard)
//$routing = [
//    'home' => ['controller' => 'FrontController', 'action' => 'homeAction'],
//    'list' => ['controller' => 'FrontController', 'action' => 'listingAction'],
//    'contact' => ['controller' => 'FrontController', 'action' => 'contactAction'],
//    'register' => ['controller' => 'FrontController', 'action' => 'registerAction'],
//    'connect' => ['controller' => 'FrontController', 'action' => 'connectAction'],
//    'detailsArticle' => ['controller' => 'FrontController', 'action' => 'detailsAction'],
//    'addArticle' => ['controller' => 'AdminController', 'action' => 'addArticleAction']
//];
//
////Routing
//$page = $_GET['p'] ?? 'home';
//
////rendu template
//if(isset($routing[$page])) {
//    $controllerName = $routing[$page]['controller'];
//    $actionName = $routing[$page]['action'];
//    $controller = new $controllerName();
//    $controller->$actionName();
//}else {
//    header('Location: home');
//}


use Blog\Controller\FrontController;
use Blog\Controller\AdminController;
use Blog\model\Db;

require_once "vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

//Routing
$page = 'home';
if (isset($_GET['p'])) {
    $page = $_GET['p'];
}

//rendu template

if ($page === 'home') {
    $controller = new FrontController();
    $controller->homeAction();
} elseif ($page === 'list') {
    $controller = new FrontController();
    $controller->listingAction();
} elseif ($page === 'contact') {
    $controller = new FrontController();
    $controller->contactAction();
} elseif ($page === 'register') {
    $controller = new FrontController();
    $controller->registerAction();
} elseif ($page === 'connect') {
    $controller = new FrontController();
    $controller->connectAction();
} elseif ($page === 'addArticle') {
    $controller = new AdminController();
    $controller->addArticleAction();
} elseif ($page === 'detailsArticle') {
    $controller = new FrontController();
    $controller->detailsAction();
} elseif ($page === 'usersList'){
    $controller = new FrontController();
    $controller->usersListAction();
} elseif ($page === 'profil'){
    $controller = new FrontController();
    $controller->profilAction();
}else {
        header('Location: home');
    }

$pdo = Db::getDb();

$users = $pdo->query('SELECT pseudo, email, is_admin FROM users');

