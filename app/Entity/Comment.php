<?php

namespace Blog\Entity;

use Exception;

/**
 * Class Comment
 */
class Comment
{
    public const SHORT_FORMAT_DATE = 'd/m/Y';
    public const FULL_FORMAT_DATE = 'd/m/Y';

    /**
     * @var int|null
     */
    public ?int $id;

    /**
     * @var string
     */
    public string $title;

    /**
     * @var string
     */
    public string $content;

    /**
     * @var \DateTimeInterface
     */
    public \DateTimeInterface $createdAt;

    /**
     * @var User
     */
    public User $author;

    /**
     * @var Article
     */
    public Article $article;

    /** @var bool */
    public bool $isValid = false;

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
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * @param User $author
     */
    public function setAuthor(User $author): void
    {
        $this->author = $author;
    }

    /**
     * @return Article
     */
    public function getArticle(): Article
    {
        return $this->article;
    }

    /**
     * @param Article $article
     */
    public function setArticle(Article $article): void
    {
        $this->article = $article;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getShortFormattedDate(): string
    {
        $createdAt = $this->getCreatedAt();
        return $createdAt->format(self::SHORT_FORMAT_DATE);
    }

    /**
     * @return string
     */
    public function getFullFormattedDate(): string
    {
        $createdAt = $this->getCreatedAt();
        return $createdAt->format(self::FULL_FORMAT_DATE);
    }

    /**
     * @param \DateTimeInterface $createdAt
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @param string $title
     * @param string $content
     * @param User $author
     * @param Article $article
     * @return static
     * @throws Exception
     */
    public static function create(
        string $title,
        string $content,
        User $author,
        Article $article
    ): self {
        $comment = new self();
        $comment->setTitle($title);
        $comment->setContent($content);
        $comment->setAuthor($author);
        $comment->setArticle($article);
        $comment->setCreatedAt(new \DateTime());
        return $comment;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->isValid;
    }

    /**
     * @param bool $isValid
     */
    public function setIsValid(bool $isValid): void
    {
        $this->isValid = $isValid;
    }
}

