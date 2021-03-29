<?php


namespace Blog\Controller;


use Blog\model\Db;

class ProfilController
{

}

session_start();

$pdo = Db::getDb();

if (isset($_GET['id']) AND $_GET > 0)
{
    $getId = intval($_GET['id']);
    $reqUser = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $reqUser->excecute(array($getId));
    $userInfo = $reqUser->fetch();
}

if ($userInfo['id'] == $_SESSION['id'])
{

}