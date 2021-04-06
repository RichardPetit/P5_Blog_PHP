<?php


namespace Blog\Entity;

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
    public \DateTimeInterface $createdAd;

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
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getCreatedAd(): \DateTimeInterface
    {
        return $this->createdAd;
    }

    /**
     * @param \DateTimeInterface $createdAd
     */
    public function setCreatedAd(\DateTimeInterface $createdAd): void
    {
        $this->createdAd = $createdAd;
    }


}
