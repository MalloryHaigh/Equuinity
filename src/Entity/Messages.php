<?php

namespace App\Entity;

use App\Repository\MessagesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MessagesRepository::class)
 */
class Messages
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    private User $to_user;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    private User $from_user;

    /**
     * @ORM\Column(type="text")
     */
    private $message_type;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private string $message_topic;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private string $message_body;

    /**
     * @ORM\Column(type="datetime")
     */
    private $message_sent;

    /**
     * @ORM\Column(type="text")
     */
    private $message_status;

    /**
     * @return mixed
     */
    public function getMessageStatus()
    {
        return $this->message_status;
    }

    /**
     * @param mixed $message_status
     */
    public function setMessageStatus($message_status): void
    {
        $this->message_status = $message_status;
    }

    /**
     * @return mixed
     */
    public function getMessageSent()
    {
        return $this->message_sent;
    }

    /**
     * @param mixed $message_sent
     */
    public function setMessageSent($message_sent): void
    {
        $this->message_sent = $message_sent;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToUser(): User
    {
        return $this->to_user;
    }

    public function setToUser(User $to_user): self
    {
        $this->to_user = $to_user;

        return $this;
    }

    public function getFromUser(): User
    {
        return $this->from_user;
    }

    public function setFromUser(User $from_user): self
    {
        $this->from_user = $from_user;

        return $this;
    }

    public function getMessageTopic(): ?string
    {
        return $this->message_topic;
    }

    public function setMessageTopic(?string $message_topic): self
    {
        $this->message_topic = $message_topic;

        return $this;
    }

    public function getMessageBody(): ?string
    {
        return $this->message_body;
    }

    public function setMessageBody(?string $message_body): self
    {
        $this->message_body = $message_body;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMessageType()
    {
        return $this->message_type;
    }

    /**
     * @param mixed $message_type
     */
    public function setMessageType($message_type): void
    {
        $this->message_type = $message_type;
    }
}
