<?php

namespace Blog\Entity;

use Blog\Exception\UserNotFoundException;
use Blog\Exception\UserNotActiveException;


/**
 * Class User
 */
class User
{

    /**
     * @var int|null
     */
    public ?int $id;

    /**
     * @var string
     */
    public string $pseudo;

    /**
     * @var string
     */
    public string $email;

    /**
     * @var string
     */
    public string $password;

    /**
     * @var bool
     */
    public bool $isAdmin = false;

    /**
     * @var bool
     */
    public bool $isActive = true;

    /**
     * @var string
     */
    public ?string $avatar;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getPseudo(): string
    {
        return $this->pseudo;
    }

    /**
     * @param string $pseudo
     */
    public function setPseudo(string $pseudo): void
    {
        $this->pseudo = $pseudo;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    /**
     * @param bool $isAdmin
     */
    public function setIsAdmin(bool $isAdmin): void
    {
        $this->isAdmin = $isAdmin;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    /**
     * @return string
     */
    public function getAvatar(): string
    {
        return $this->avatar;
    }

    /**
     * @param string $avatar
     */
    public function setAvatar(?string $avatar): void
    {
        $this->avatar = $avatar;
    }

    /**
     * @return string
     */
    public function getInitials(): string
    {
        return $this->initials;
    }

    /**
     * @param string $password
     * @throws UserNotFoundException
     */
    public function verifyPassword(string $password):void
    {
        if (!password_verify($password, $this->getPassword())) {
            throw new UserNotFoundException();
        }
    }

    /**
     * @throws UserNotActiveException
     */
    public function verifyStatus():void
    {
        if (!$this->isActive()) {
            throw new UserNotActiveException();
        }
    }

    /**
     * @param string $pseudo
     * @param string $email
     * @param string $password
     * @return static
     */
    public static function create(
        string $pseudo,
        string $email,
        string $password
    ): self {
        $user = new self();
        $user->setPseudo($pseudo);
        $user->setEmail($email);
        $user->setPassword($password);
        return $user;
    }
}

