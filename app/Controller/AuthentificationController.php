<?php


namespace Blog\Controller;
use Blog\model\Db;
use Blog\model\Users;

class AuthentificationController extends AbstractController
{

    public function userRegisterAction($pseudo, $email, $password)
    {
        $pseudo = htmlspecialchars ($_POST['pseudo'] )?? '';
        $email = htmlspecialchars($_POST['email']) ?? '';
        $password = password_hash ($_POST['password'] , PASSWORD_DEFAULT) ?? '';
        $userRegisterSubmitted = false;

        if (isset($_POST['add'])) {
            //@TODO : La validation des donnees
            $user = Users::userRegister($pseudo, $email, $password);
            if($user) {
                $userRegisterSubmitted = true;
            }
        }

        $this->render("front", "register.html.twig", [
            'pseudo' => $pseudo,
            'email' => $email,
            'password' => $password,
            'userRegisterSubmitted' => $userRegisterSubmitted,
        ]);
    }



}