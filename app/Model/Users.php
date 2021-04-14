<?php


namespace Blog\Model;


use Blog\Entity\User;
use Blog\Model\Connector\PDO;
use function var_dump;

class Users
{
    public static function getUser($id) : User
    {
        //On récupère l'instance de PDO
        $pdo = PDO::getInstance();
        //On récupère grâce à PDO l'enregistrement MySQL de l'user id = $id
        $req = $pdo->prepare("SELECT id, pseudo, email, is_admin, is_active, avatar FROM users WHERE id = ? ");
        $req->execute([$id]);
        //On fetch ici le résultat pour avoir l'enregistrement retourné par la requête
        $userPDO = $req->fetch();
        //On retourne ensuite l'Entité User hydraté depuis l'enregistrement PDO
        return self::hydrateEntity($userPDO);
    }

    public static function add(User $user)
    {
        $pdo = PDO::getInstance();
        try {
            $pseudo = $user->getPseudo();
            $email = $user->getEmail();
            $password = $user->getPassword();
            //Ici, d'après moi c'est ta DB qui doit mettre des valeurs par défaut pour is_active et is_admin
            //Quand on crée un user, par défaut il est actif et il n'est pas admin
            //Et pour avatar, au final tu ajoutes ton avatar depuis le profil mais pas à la création je pense
            //Donc en DB => is_active default 1 et is_admin default 0
            //Mais sinon c'est top ! Bravo !
            $sql = "INSERT INTO users(pseudo, email, password) VALUES (?, ?, ?)";
            $pdo->prepare($sql)->execute([$pseudo, $email, $password]);
        } catch (\Exception $e){
            var_dump($e->getMessage());
            exit;
        }
        return $user;
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
        //A ce moment là on a une Entité User parfaitement instantiée et hydratée, on retourne donc le résultat
        return $userEntity;
    }


}
