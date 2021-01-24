<?php

namespace App\Entity;

use App\Repository\ConfigRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConfigRepository::class)
 */
class Config
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $forum_rules;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getForumRules(): ?string
    {
        return $this->forum_rules;
    }

    public function setForumRules(?string $forum_rules): self
    {
        $this->forum_rules = $forum_rules;

        return $this;
    }
}
