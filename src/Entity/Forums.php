<?php

namespace App\Entity;

use App\Repository\ForumsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ForumsRepository::class)
 */
class Forums
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $forum_name;

    /**
     * @ORM\Column(type="text")
     */
    private $forum_description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $forum_icon;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $access;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getForumName(): ?string
    {
        return $this->forum_name;
    }

    public function setForumName(string $forum_name): self
    {
        $this->forum_name = $forum_name;

        return $this;
    }

    public function getForumDescription(): ?string
    {
        return $this->forum_description;
    }

    public function setForumDescription(string $forum_description): self
    {
        $this->forum_description = $forum_description;

        return $this;
    }

    public function getForumIcon(): ?string
    {
        return $this->forum_icon;
    }

    public function setForumIcon(?string $forum_icon): self
    {
        $this->forum_icon = $forum_icon;

        return $this;
    }

    public function getAccess(): ?string
    {
        return $this->access;
    }

    public function setAccess(?string $access): self
    {
        $this->access = $access;

        return $this;
    }
}
