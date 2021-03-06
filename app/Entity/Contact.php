<?php


namespace Blog\Entity;


/**
 * Class Contact
 */
class Contact
{
    /**
     * @var string
     */
    public string $email;

    /**
     * @var string
     */
    public string $name;

    /**
     * @var string
     */
    public string $subject;

    /**
     * @var string
     */
    public string $message;

    /**
     * @var \DateTimeInterface
     */
    public \DateTimeInterface $sendedAt;

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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getSendedAt(): \DateTimeInterface
    {
        return $this->sendedAt;
    }

    /**
     * @param \DateTimeInterface $sendedAt
     */
    public function setSendedAt(\DateTimeInterface $sendedAt): void
    {
        $this->sendedAt = $sendedAt;
    }

    /**
     * @param string $email
     * @param string $name
     * @param string $subject
     * @param string $message
     * @return static
     */
    public static function create(
        string $email,
        string $name,
        string $subject,
        string $message
    ): self {
        $contactMessage = new self();
        $contactMessage->setEmail($email);
        $contactMessage->setName($name);
        $contactMessage->setSubject($subject);
        $contactMessage->setMessage($message);
        $contactMessage->setSendedAt(new \DateTime());
        return $contactMessage;
    }
}

