<?php

namespace Blog\Model;

use Blog\Entity\User;
use Blog\Exception\UserNotFoundException;
use Blog\Model\Connector\PDO;

class Users
{
    /**
     * @param $id
     * @return User
     * @throws UserNotFoundException
     */
    public static function getUser($id) : User
    {
        $pdo = PDO::getInstance();
        $req = $pdo->prepare("SELECT * FROM users WHERE id = ? ");
        $req->execute([$id]);
        $userPDO = $req->fetch();
        if (!$userPDO){
            throw new UserNotFoundException();
        }
        return self::hydrateEntity($userPDO);

    }

    /**
     * @return array
     */
    public static function  getAllUsers()
    {
        $pdo = PDO::getInstance();
        try {
            $allUsers = $pdo->query('SELECT * FROM users');
        } catch (\Exception $e) {
            echo "Une erreur c'est produite." . $e->getMessage();
        }
        $usersEntities = [];
        foreach ($allUsers as $userPDO) {
            $usersEntities[] = self::hydrateEntity($userPDO);
        }
        return $usersEntities;
    }

    /**
     * @param $email
     * @return User
     * @throws UserNotFoundException
     */
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

    /**
     * @param User $user
     * @return User|string
     */
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

    /**
     * @param $id
     * @return mixed
     */
    public function userProfil($id)
    {
        return $this->getProfil('users', 'User',$id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function getProfil($id)
    {
        $pdo = PDO::getInstance();
        $req = $pdo->prepare("SELECT id, pseudo, email, is_admin, is_active, avatar FROM users WHERE id = ? ");
        $req->execute(array($id));
        $data = $req->fetch(\PDO::FETCH_ASSOC);
        return $data;
    }

    /**
     * @param int $id
     * @param bool $active
     */
     public static function changeUserStatus(int $id, bool $active = true)
     {
         $pdo = PDO::getInstance();
         try {
             $active = (int)$active;
             $query = "UPDATE users SET is_active = ? WHERE id = ?";
             $req = $pdo->prepare($query);
             $req->execute([$active, $id]);
         } catch (\Exception $e){
             echo "Erreur de connexion à la base de données. Exception reçue : " . $e->getMessage();
         }
     }

    /**
     * @param int $id
     */
    public static function changeUserOnActive(int $id)
    {
        self::changeUserStatus($id);
    }

    /**
     * @param int $id
     */
    public static function changeUserOnInactive(int $id)
    {
        self::changeUserStatus($id, false);
    }

    /**
     * @param int $id
     * @param bool $admin
     */
    public static function changeUserRole(int $id, bool $admin = true)
    {
        $pdo = PDO::getInstance();
        try {
            $admin = (int)$admin;
            $query = "UPDATE users SET is_admin = ? WHERE id = ?";
            $req = $pdo->prepare($query);
            $req->execute([$admin, $id]);
        } catch (\Exception $e){
            echo "Erreur de connexion à la base de données. Exception reçue : " . $e->getMessage();
        }
    }

    /**
     * @param int $id
     */
    public static function changeUserToAdmin(int $id)
    {
        self::changeUserRole($id);
    }

    /**
     * @param int $id
     */
    public static function changeAdminToUser(int $id)
    {
        self::changeUserRole($id, false);
    }

    /**
     * @param object $userFromDb
     * @return User
     */
    public static function hydrateEntity(object $userFromDb) : User
    {
        $userEntity = new User();
        $userEntity->setId($userFromDb->id);
        $userEntity->setPseudo($userFromDb->pseudo);
        $userEntity->setEmail($userFromDb->email);
        $userEntity->setIsAdmin($userFromDb->is_admin == 1);
        $userEntity->setIsActive($userFromDb->is_active == 1);
        $userEntity->setAvatar($userFromDb->avatar);
        $userEntity->setPassword($userFromDb->password);
        return $userEntity;
    }
}
