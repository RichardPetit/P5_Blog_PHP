<?php


namespace Blog\Model;


use Blog\Entity\User;
use Blog\Exception\EmailExistsException;
use Blog\Exception\PseudoExistsException;
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

    public static function isPseudoAlreadyExists(string $pseudo): bool
    {
        $pdo = PDO::getInstance();
        $reqPseudo = $pdo->prepare("SELECT COUNT(*) AS nb FROM users WHERE pseudo = ?");
        $reqPseudo->execute([$pseudo]);
        $pseudo = $reqPseudo->fetch();
        return $pseudo->nb === 1;
    }

    public static function isEmailAlreadyExists(string $email): bool
    {
        $pdo = PDO::getInstance();
        $reqEmail = $pdo->prepare("SELECT COUNT(*) AS nb FROM users WHERE email = ?");
        $reqEmail->execute([$email]);
        $email = $reqEmail->fetch();
        return $email->nb === 1;
    }

    public static function encodePassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * @param User $user
     * @return User
     * @throws EmailExistsException
     * @throws PseudoExistsException
     */
    public static function add(User $user): User
    {
        $pdo = PDO::getInstance();
        $pseudo = $user->getPseudo();
        $email = $user->getEmail();
        $password = self::encodePassword($user->getPassword());
        $pseudoExists = self::isPseudoAlreadyExists($pseudo);
        $emailExists = self::isEmailAlreadyExists($pseudo);
        if ($pseudoExists) {
            throw new PseudoExistsException();
        }
        if($emailExists) {
            throw new EmailExistsException();
        }

        $sql = "INSERT INTO users(pseudo, email, password) VALUES (?, ?, ?)";
        $pdo->prepare($sql)->execute([$pseudo, $email, $password]);
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
