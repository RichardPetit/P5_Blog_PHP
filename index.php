<?php

use Blog\Controller\FrontController;

require_once "vendor/autoload.php";
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
}





////Récupérer derniers articles
//function getArticles(){
//    $pdo = new PDO('mysql:dbname=blog;host=localhost:3309', 'root', 'root');
//    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // permet d'indiquer qu'on veut des exceptions en cas d'erreur
//    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ); //demande  que ça renvoi sous forme d'objet et non d'un tableau
//    $articles = $pdo->query('SELECT * FROM articles ORDER BY id DESC limit 10');
//    return $articles;
//}
//
//$articles=getArticles();
//
//
////Récupérer derniers commentraires
//
// function getComments()
//{
//    $pdo = new PDO('mysql:dbname=blog;hots=localhost:3309', 'root', 'root');
//    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
//    $comments = $pdo->query('SELECT * FROM comments ORDER BY id');
//    return $comments;
//}
//$comments=getComments();






