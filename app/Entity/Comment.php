<?php


namespace Blog\Entity;

use Assert\Assertion;
use Exception;

/**
 * Class Comment
 */
class Comment
{
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
     * @return \DateTimeInterface
     */
    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeInterface $createdAt
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
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
        return self::validate($comment);
    }

    public static function validate(self $comment)
    {
        Assertion::notEmpty($comment->getTitle(), 'Le champs titre ne peut être vide.');
        Assertion::minLength($comment->getTitle(), 5, 'Le titre doit faire 5 caractères minimum.' );
        Assertion::maxLength($comment->getTitle(), 100, 'Le titre ne doit pas excéder 100 caractères.' );
        Assertion::notEmpty($comment->setContent(), 'Le champs contenu ne peut être vide.');
        return $comment;
    }

}