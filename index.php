<?php
session_start();
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


//Page d'inscription

//
    $pdo = Db::getDb();
//    if(isset($_POST['formRegister'])) {
//        $pseudo = htmlspecialchars($_POST['pseudo']);
//        $email = htmlspecialchars($_POST['email']);
//        $email2 = htmlspecialchars($_POST['email2']);
//        $password = sha1($_POST['password']);
//        $password2 = sha1($_POST['password2']);
//
//        if (!empty($_POST['pseudo']) AND !empty($_POST['email']) AND !empty($_POST['email2'])
//            AND !empty($_POST['password']) AND !empty($_POST['password2']))
//        {
//            $pseudoLength = strlen($pseudo);
//            if ($pseudoLength <= 45) {
//                $reqPseudo = $pdo->prepare('SELECT * FROM users WHERE pseudo = ?');
//                $reqPseudo->execute(array($pseudo));
//                $pseudoExist = $reqPseudo->rowCount();
//                if ($pseudoExist == 0) {
//                    if ($email == $email2) {
//                        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
//                            $reqEmail = $pdo->prepare('SELECT * FROM users WHERE email = ?');
//                            $reqEmail->execute(array($email));
//                            $emailExist = $reqEmail->rowCount();
//                            if ($emailExist == 0) {
//                                if ($password == $password2) {
//                                    $insertNewUser = $pdo->prepare("INSERT INTO users (pseudo, email, password, is_admin, is_active) VALUES (?, ?, ?, 0, 1)");
//                                    $insertNewUser->execute(array($pseudo, $email, $password));
//                                    $message = "Votre compte à bien été créé.";
//                                } else {
//                                    $message = "Vos mots de passes ne correspondent pas.";
//                                }
//                            } else {
//                                $message = "Adresse email déjà utilisée";
//                            }
//                        } else {
//                            $message = "Votre adresse email n'est pas valide";
//                        }
//                    } else {
//                        $message = "Vos adresses emails ne correspondent pas.";
//                    }
//                }else{
//                    $message ="Votre pseudo est déjà utilisé.";
//                }
//            } else{
//                $message = "Votre pseudo ne doit pas dépasser 45 caractères.";
//            }
//        } else{
//            $message = "Tous les champs doivent être complétés.";
//        }
//    }
//?>
<!---->
<!--<!DOCTYPE html>-->
<!--<html>-->
<!---->
<!--    <body>-->
<!---->
<!--        <div class="formRegister" align="center">-->
<!--            <h2>Inscription</h2>-->
<!--            <hr class="star-primary">-->
<!--            <br>-->
<!---->
<!--            <form method="post" action="">-->
<!--                <p>-->
<!--                    <label for="pseudo">Pseudo :</label><br>-->
<!--                    <input type="text" placeholder="Votre pseudo"-->
<!--                           name="pseudo" id="pseudo" value="--><?php //if (isset($pseudo)) {echo $pseudo;}?><!--" class="form-control">-->
<!--                </p>-->
<!--                <p>-->
<!--                    <label for="email">Email :</label>-->
<!--                    <input type="email" placeholder="Votre email" id="email"-->
<!--                           name="email" value="" class="form-control">-->
<!--                </p>-->
<!--                <p>-->
<!--                    <label for="email2">Confirmation de l'email :</label>-->
<!---->
<!--                    <input type="email" placeholder="Confirmez votre email" id="email2"-->
<!--                           name="email2" value="" class="form-control">-->
<!--                </p>-->
<!--                <p>-->
<!--                    <label for="password">Mot de passe :</label>-->
<!--                    <input type="password" placeholder="Votre mot de passe" id="password" name="password"-->
<!--                           class="form-control">-->
<!--                </p>-->
<!--                <p>-->
<!--                    <label for="password2">Confirmation du mot de passe :</label>-->
<!--                    <input type="password" placeholder="Confirmez votre mdp" id="password2" name="password2"-->
<!--                           class="form-control">-->
<!--                </p>-->
<!--                <br>-->
<!--                <p>-->
<!--                    <input type="submit" class="btn btn-success" value="Je m'inscrit" name="formRegister">-->
<!--                </p>-->
<!--            </form>-->
<!--            --><?php
//                if (isset($message)){
//                    echo '<font color="red">' .$message.'</font>';
//                }
//            ?>
<!--            <br>-->
<!--        </div>-->
<!---->
<!--    </body>-->
<!--</html>-->


<!--Page de connexion-->



<!--D'abord se connecté à la BDD mais déjà fait plus haut-->
<?php

if (isset($_POST['formConnexion']))
{
    $pseudoConnect = htmlspecialchars(($_POST['pseudoConnect']));
    $passwordConnect = sha1($_POST['passwordConnect']);

    if (!empty($pseudoConnect) AND !empty($passwordConnect))
    {
        $reqUser = $pdo->prepare('SELECT * FROM users WHERE pseudo = ? AND password = ?');
        $reqUser->execute(array($pseudoConnect, $passwordConnect));
        $userExist = $reqUser->rowCount();
        if ($userExist == 1){
            $userInfo = $reqUser->fetch();
            $_SESSION['id'] = $userInfo['id'];
            $_SESSION['pseudo'] = $userInfo['pseudo'];
            $_SESSION['email'] = $userInfo['email'];
            header('Location: ?p=profil&id='.$_SESSION['id']);
        }else{
            $messageConnect ="Pseudo ou mot de passe incorrect.";
        }
    } else {
        $messageConnect = "Tous les champs doivent être complétés.";
    }
}
?>






<!DOCTYPE html>
<html>

<body>

<div class="formRegister" align="center">
    <h2>Connexion</h2>
    <hr class="star-primary">
    <br>

    <form method="post" action="">
        <p>
            <label for="pseudoConnect">Pseudo :</label><br>
            <input type="text" placeholder="Votre pseudo"
                   name="pseudoConnect" id="pseudoConnect" value="<?php if (isset($pseudo)) {echo $pseudo;}?>" class="form-control">
        </p>
        <p>
            <label for="passwordConnect">Mot de passe :</label>
            <input type="password" placeholder="Votre mot de passe" id="passwordConnect" name="passwordConnect"
                   class="form-control">
        </p>
        <br>
        <p>
            <input type="submit" class="btn btn-success" value="Se connecter" name="formConnexion">
        </p>
    </form>
    <?php
    if (isset($messageConnect)){
        echo '<font color="red">' .$messageConnect.'</font>';
    }
    ?>
    <br>
</div>

</body>
</html>