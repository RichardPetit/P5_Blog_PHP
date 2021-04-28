<?php


namespace Blog\Entity;

use Exception;

/**
 * Class Article
 */
class Article
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
     * @var string
     */
    public string $summary;

    /**
     * @var User
     */
    public User $author;

    /**
     * @var \DateTimeInterface
     */
    public \DateTimeInterface $createdAt;

    /**
     * @var string
     */
    public string $picture;

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
     * @return string
     */
    public function getSummary(): string
    {
        return $this->summary;
    }

    /**
     * @param string $summary
     */
    public function setSummary(string $summary): void
    {
        $this->summary = $summary;
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
     * @param string $summary
     * @param User $author
     * @return static
     * @throws Exception
     */
    public static function create(
        string $title,
        string $content,
        string $summary,
            User $author
    ): self {
        //On instantie l'Entité Article et on set les paramètres nécessaires
        $article = new self();
        $article->setTitle($title);
        $article->setContent($content);
        $article->setSummary($summary);
        $article->setAuthor($author);
        $article->setCreatedAt(new \DateTime());
        return $article;
    }



}
