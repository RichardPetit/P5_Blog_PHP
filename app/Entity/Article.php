<?php


namespace Blog\Entity;

use Exception;

/**
 * Class Article
 */
class Article
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
        return self::validate($article);
    }

    public static function validate(self $article)
    {
        if(strlen($article->getTitle()) < 5) {
            throw new Exception('Le titre doit contenir au moins 5 caractères');
        }
        //Logique de validation a ajouter
        return $article;
    }


}
