<?php


namespace Blog\Controller;


class UserController extends AbstractController
{

    public function userRegister()
    {
        $pdo = Db::getDb();
        if(isset($_POST['formRegister'])) {
            $pseudoRegister = htmlspecialchars($_POST['pseudo']);
            $emailRegister = htmlspecialchars($_POST['email']);
            $email2Register = htmlspecialchars($_POST['email2']);
            $passwordRegister = sha1($_POST['password']);
            $password2Register = sha1($_POST['password2']);

            if (!empty($_POST['pseudo']) and !empty($_POST['mail']) and !empty($_POST['mail2']) and !empty($_POST['password'])
                and !empty($_POST['password2'])) {
                $pseudoLength = strlen($pseudoRegister);
                if ($pseudoLength <= 45) {
                    $reqPseudo = $pdo->prepare("SELECT * FROM users WHERE pseudo = ?");
                    $reqPseudo->execute(array($pseudoRegister));
                    $pseudoExist = $reqPseudo->rowCount();
                    if ($pseudoExist == 0) {
                        if ($emailRegister == $email2Register) {
                            if (filter_var($emailRegister, FILTER_VALIDATE_EMAIL)) {
                                $reqEmail = $pdo->prepare("SELECT * FROM users WHERE email = ?");
                                $reqEmail->execute(array($emailRegister));
                                $emailExist = $reqEmail->rowCount();
                                if ($emailExist == 0) {
                                    if ($passwordRegister == $password2Register) {
                                        $insertNewUser = $pdo->prepare("INSERT INTO users(name, forname, email, password) VALUES (?, ?, ?)");
                                        $insertNewUser->execute(array($pseudoRegister, $emailRegister, $passwordRegister));
                                        $message = "Votre compte à bien été créé.";
                                        header('Location: index.php');
                                    } else {
                                        $message = "Vos mots de passes ne correspondent pas.";
                                    }
                                } else {
                                    $message = "Cette adresse email est déjà utilisée.";
                                }
                            } else {
                                $message = "Votre adresse email n'est pas valide.";
                            }
                        } else {
                            $message = "Vos adresses emails ne correspondent pas.";
                        }
                    } else {
                        $message = "Ce pseudo est déjà utilisé.";
                    }
                } else {
                    $message = "Votre pseudo ne peut dépasser 45 caractères.";
                }
            } else {
                $message = "Tous les champs doivent être complétés";
            }
        }
        $this->render("front", "register.html.twig", [
            'pseudo' => $pseudoRegister,
            'email' => $emailRegister,
            'password' => $passwordRegister
    ]);
    }

}

