<?php

namespace App\Entity;

use App\Repository\ForumRepliesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ForumRepliesRepository::class)
 */
class ForumReplies
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
    private $thread_id;

    /**
     * @ORM\Column(type="text")
     */
    private $post_body;

    /**
     * @ORM\Column(type="datetime")
     */
    private $posted_date;

    /**
     * @ORM\ManyToOne(targetEntity="User", cascade={"all"})
     */
    private ?User $posted_by;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getThreadId(): ?int
    {
        return $this->thread_id;
    }

    public function setThreadId(int $thread_id): self
    {
        $this->thread_id = $thread_id;

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

    public function getPostedBy(): ?User
    {
        return $this->posted_by;
    }

    public function setPostedBy(User $posted_by): self
    {
        $this->posted_by = $posted_by;

        return $this;
    }
}
