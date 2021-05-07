<?php


namespace Blog\Model;


use Blog\Entity\User;
use Blog\Exception\UserNotFoundException;
use Blog\Model\Connector\PDO;

class Users
{
    public static function getUser($id) : User
    {
        //On récupère l'instance de PDO
        $pdo = PDO::getInstance();
        //On récupère grâce à PDO l'enregistrement MySQL de l'user id = $id
        $req = $pdo->prepare("SELECT * FROM users WHERE id = ? ");
        $req->execute([$id]);
        //On fetch ici le résultat pour avoir l'enregistrement retourné par la requête
        $userPDO = $req->fetch();
        //On retourne ensuite l'Entité User hydraté depuis l'enregistrement PDO
        if (!$userPDO){
            throw new UserNotFoundException();
        }
        return self::hydrateEntity($userPDO);

    }

    public static function  getAllUsers()
    {
        $pdo = PDO::getInstance();
        try {
            $allUsers = $pdo->query('SELECT id, pseudo, email, is_admin FROM users');
        } catch (\Exception $e) {
            echo "Une erreur c'est produite." . $e->getMessage();
        }
        return $allUsers;
    }

    public static function getUserByEmail($email) : User
    {
        $pdo = PDO::getInstance();

        $req = $pdo->prepare("SELECT * FROM users WHERE email = ? ");
        $req->execute([$email]);
        $userPDO = $req->fetch();
        if (!$userPDO){
            throw new UserNotFoundException();
        }
        return self::hydrateEntity($userPDO);
    }



    public static function add(User $user)
    {
        $pdo = PDO::getInstance();
        try {
            $message = "Votre compte a bien été créé.";
            $pseudo = $user->getPseudo();
            $email = $user->getEmail();
            $password = password_hash($user->getPassword(), PASSWORD_DEFAULT);
            $reqPseudo = $pdo->prepare("SELECT * FROM users WHERE pseudo = ?");
            $reqPseudo->execute(array($pseudo));
            $pseudoExist = $reqPseudo->rowCount();
            $reqEmail = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $reqEmail->execute(array($email));
            $emailExist = $reqEmail->rowCount();
            if ($pseudoExist == 0 ){
                if ($emailExist == 0){
                    $sql = "INSERT INTO users(pseudo, email, password) VALUES (?, ?, ?)";
                    $pdo->prepare($sql)->execute([$pseudo, $email, $password]);
                    return $message;
                } else{
                    $message = "Cette adresse email est déjà utilisée.";
                    return $message;
                }
            }else {
                $message = "Ce pseudo est déjà utilisé.";
                return $message;
            }
            } catch (\Exception $e){
            echo "Une erreur c'est produite" . $e->getMessage();
        }
        return $user;
    }

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


    public static function hydrateEntity($userFromDb) : User
    {
        //$userFromDB correspond à l'enregistrement PDO
        //On instantie l'entité User et on hydrate ses paramètres depuis le PDO
        $userEntity = new User();
        $userEntity->setId($userFromDb->id);
        $userEntity->setPseudo($userFromDb->pseudo);
        $userEntity->setEmail($userFromDb->email);
        $userEntity->setIsAdmin($userFromDb->is_admin == 1);
        $userEntity->setIsActive($userFromDb->is_active == 1);
        $userEntity->setAvatar($userFromDb->avatar);
        $userEntity->setPassword($userFromDb->password);
        //A ce moment là on a une Entité User parfaitement instantiée et hydratée, on retourne donc le résultat
        return $userEntity;
    }



}
