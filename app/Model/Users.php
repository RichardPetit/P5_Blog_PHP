<?php


namespace Blog\Model;


use Blog\Entity\User;
use Blog\Model\Connector\PDO;

class Users
{
    public static function getUser($id)
    {
        $pdo = PDO::getInstance();
        $req = $pdo->prepare("SELECT id, pseudo, email, is_admin, is_active, avatar FROM users WHERE id = ? ");
        $req->execute(array($id));
        $data = $req->fetch(\PDO::FETCH_ASSOC);
        return $data;
    }

    public static function hydrateEntity($userFromDb)
    {
        $userEntity = new User();
        $userEntity->setPseudo($userFromDb['pseudo']);
        $userEntity->setEmail($userFromDb['email']);
        $userEntity->setIsAdmin($userFromDb['is_admin']);
        $userEntity->setIsActive($userFromDb['is_active']);
        return $userEntity;
    }
}
