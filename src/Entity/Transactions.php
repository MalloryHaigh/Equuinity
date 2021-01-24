<?php

namespace App\Entity;

use App\Repository\TransactionsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TransactionsRepository::class)
 */
class Transactions
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
    private $player_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $purchase_date;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity_credits;

    /**
     * @ORM\Column(type="integer")
     */
    private $amount;

    /**
     * @ORM\Column(type="integer")
     */
    private $fulfilled;

    /**
     * @return mixed
     */
    public function getFulfilled()
    {
        return $this->fulfilled;
    }

    /**
     * @param mixed $fulfilled
     */
    public function setFulfilled($fulfilled): void
    {
        $this->fulfilled = $fulfilled;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayerId(): ?int
    {
        return $this->player_id;
    }

    public function setPlayerId(int $player_id): self
    {
        $this->player_id = $player_id;

        return $this;
    }

    public function getPurchaseDate(): ?\DateTimeInterface
    {
        return $this->purchase_date;
    }

    public function setPurchaseDate(\DateTimeInterface $purchase_date): self
    {
        $this->purchase_date = $purchase_date;

        return $this;
    }

    public function getQuantityCredits(): ?int
    {
        return $this->quantity_credits;
    }

    public function setQuantityCredits(int $quantity_credits): self
    {
        $this->quantity_credits = $quantity_credits;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }
}
