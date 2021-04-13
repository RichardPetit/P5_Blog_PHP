<?php


namespace Blog\model;


use Blog\Entity\User;

class Users extends Db
{
    private $_id;
    private $_pseudo;
    private $_email;
    private $_password;
    private $_is_admin;
    private $_is_active;
    private $_avatar;

    //Fonction qui récupère les utilisateurs en BDD
    public static function getUsers()
    {
        $pdo = Db::getDb();
        try {
            $users = $pdo->query('SELECT pseudo, email, is_admin FROM users');

        }
        catch (\Exception $e ){
            var_dump($e->getMessage());
            exit();
        }
        return $users;
    }

    //Fonction qui récupère un utilisateur en BDD
//    public function userProfile($id)
//    {
//        return $this->getProfile('users', 'User',$id);
//    }

    public static function getProfile($id)
    {
        $pdo = Db::getDb();
        $req = $pdo->prepare("SELECT id, pseudo, email, is_admin, is_active, avatar FROM users WHERE id = ? ");
        $req->execute(array($id));
        $data = $req->fetch(\PDO::FETCH_ASSOC);
        return $data;
    }

    public static function getEntity($userFromDb)
    {
        $userEntity = new User();
        $userEntity->setPseudo($userFromDb['pseudo']);
        $userEntity->setEmail($userFromDb['email']);
        $userEntity->setIsAdmin($userFromDb['is_admin']);
        $userEntity->setIsActive($userFromDb['is_active']);
        return $userEntity;
    }

    public function userConnection()
    {
        $pdo = Db::getDb();
        $req = $pdo->prepare("SELECT id, password FROM users WHERE pseudo = ? ");
        $req->execute([
            'pseudo' => $pseudo
        ]);
        $result = $req->fetch();

        $isPasswordCorrect = password_verify($_POST['password'], $result['password']);
        if (!$result)
        {
            echo 'Identifiant ou mot de passe incorrecte.';
        } else {
            if ($isPasswordCorrect){
                session_start();
                $_SESSION['id'] = $result['id'];
                $_SESSION['pseudo'] = $pseudo;
                echo 'Vous êtes connecté.';
            }else {
                echo 'Identifiant ou mot de passe incorrecte.';
            }
        }
    }

    public function userDisconnecttion()
    {
        session_start();

        $_SESSION = array();
        session_destroy();

        setcookie('login', '');
        setcookie('pass_hache', '');
    }

    public static function userRegister($pseudo, $email, $password)
    {
        $pdo = Db::getDb();
        $insertNewUser = $pdo->prepare("INSERT INTO users (pseudo, email, password, is_admin, is_active, avatar) VALUES (?, ?, ?, 0, 0, '')");
        return $insertNewUser->execute([$pseudo, $email, $password]);
    }

    // /!\ Ne pas effacer version avec vérifictions plus compètes

//    public function userRegister()
//    {
//        $pdo = Db::getDb();
//        if(isset($_POST['formRegister'])) {
//            $pseudoRegister = htmlspecialchars($_POST['pseudo']);
//            $emailRegister = htmlspecialchars($_POST['email']);
//            $email2Register = htmlspecialchars($_POST['email2']);
//            $passwordRegister = sha1($_POST['password']);
//            $password2Register = sha1($_POST['password2']);
//
//            if (!empty($_POST['pseudo']) and !empty($_POST['mail']) and !empty($_POST['mail2']) and !empty($_POST['password'])
//                and !empty($_POST['password2'])) {
//                $pseudoLength = strlen($pseudoRegister);
//                if ($pseudoLength <= 45) {
//                    $reqPseudo = $pdo->prepare("SELECT * FROM users WHERE pseudo = ?");
//                    $reqPseudo->execute(array($pseudoRegister));
//                    $pseudoExist = $reqPseudo->rowCount();
//                    if ($pseudoExist == 0) {
//                        if ($emailRegister == $email2Register) {
//                            if (filter_var($emailRegister, FILTER_VALIDATE_EMAIL)) {
//                                $reqEmail = $pdo->prepare("SELECT * FROM users WHERE email = ?");
//                                $reqEmail->execute(array($emailRegister));
//                                $emailExist = $reqEmail->rowCount();
//                                if ($emailExist == 0) {
//                                    if ($passwordRegister == $password2Register) {
//                                        $insertNewUser = $pdo->prepare("INSERT INTO users(pseudo, email, password,
//                                        is_admin, is_active, avatar) VALUES (?, ?, ? , 0, 0, '')");
//                                        $insertNewUser->execute([$pseudoRegister, $emailRegister, $passwordRegister]);
//                                        $message = "Votre compte à bien été créé.";
//                                        header('Location: index.php');
//                                    } else {
//                                        $message = "Vos mots de passes ne correspondent pas.";
//                                    }
//                                } else {
//                                    $message = "Cette adresse email est déjà utilisée.";
//                                }
//                            } else {
//                                $message = "Votre adresse email n'est pas valide.";
//                            }
//                        } else {
//                            $message = "Vos adresses emails ne correspondent pas.";
//                        }
//                    } else {
//                        $message = "Ce pseudo est déjà utilisé.";
//                    }
//                } else {
//                    $message = "Votre pseudo ne peut dépasser 45 caractères.";
//                }
//            } else {
//                $message = "Tous les champs doivent être complétés";
//            }
//        }
//        $this->render("front", "register.html.twig", [
//            'pseudo' => $pseudoRegister,
//            'email' => $emailRegister,
//            'password' => $passwordRegister
//        ]);
//    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setPseudo($pseudo)
    {
        $this->_pseudo = $pseudo;
    }

    public function getPseudo()
    {
        return $this->_pseudo;
    }

    public function setEmail($email)
    {
        $this->_email = $email;
    }

    public function getEmail()
    {
        return $this->_email;
    }

    public function setPassword($password)
    {
        $this->_password = $password;
    }

    public function getPassword()
    {
        return $this->_password;
    }

    public function setIsAdmin($is_admin)
    {
        $this->_is_admin = $is_admin;
    }

    public function getIsAdmin()
    {
        return $this->_is_admin;
    }


    public function setIsActive($is_active)
    {
        $this->_is_active = $is_active;
    }


    public function getIsActive()
    {
        return $this->_is_active;
    }


    public function setAvatar($avatar)
    {
        $this->_avatar = $avatar;
    }

    public function getAvatar()
    {
        return $this->_avatar;
    }
}
