<?php

namespace App\Entity;

use App\Repository\ForumThreadsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ForumThreadsRepository::class)
 */
class ForumThreads
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $board_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $thread_title;

    /**
     * @ORM\ManyToOne(targetEntity="User", cascade={"all"})
     */
    private ?User $posted_by;

    /**
     * @ORM\Column(type="text")
     */
    private $post_body;

    /**
     * @ORM\Column(type="datetime")
     */
    private $posted_date;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $last_updated;

    /**
     * @return mixed
     */
    public function getLastUpdated()
    {
        return $this->last_updated;
    }

    /**
     * @param mixed $last_updated
     */
    public function setLastUpdated($last_updated): void
    {
        $this->last_updated = $last_updated;
    }

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBoardId(): ?int
    {
        return $this->board_id;
    }

    public function setBoardId(int $board_id): self
    {
        $this->board_id = $board_id;

        return $this;
    }

    public function getThreadTitle(): ?string
    {
        return $this->thread_title;
    }

    public function setThreadTitle(string $thread_title): self
    {
        $this->thread_title = $thread_title;

        return $this;
    }

    public function getPostedBy(): ?User
    {
        return $this->posted_by;
    }

    public function setPostedBy(User $posted_by): self
    {
        $this->posted_by = $posted_by;

        return $this;
    }

    public function getPostBody(): ?string
    {
        return $this->post_body;
    }

    public function setPostBody(string $post_body): self
    {
        $this->post_body = $post_body;

        return $this;
    }

    public function getPostedDate(): ?\DateTimeInterface
    {
        return $this->posted_date;
    }

    public function setPostedDate(\DateTimeInterface $posted_date): self
    {
        $this->posted_date = $posted_date;

        return $this;
    }
}
