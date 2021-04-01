<?php


namespace Blog\model;


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
    public function userProfil($id)
    {
        return $this->getProfil('users', 'User',$id);
    }

    public static function getProfil($id)
    {
        $pdo = Db::getDb();
        $req = $pdo->prepare("SELECT id, pseudo, email, is_admin, is_active, avatar FROM users WHERE id = ? ");
        $req->execute(array($id));
        $data = $req->fetch(\PDO::FETCH_ASSOC);
        return $data;
    }


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
                                        $insertNewUser = $pdo->prepare("INSERT INTO users(pseudo, email, password, 
                                        is_admin, is_active, avatar) VALUES (?, ?, ? , 0, 0, '')");
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