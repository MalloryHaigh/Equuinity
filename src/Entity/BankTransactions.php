<?php

namespace App\Entity;

use App\Repository\BankTransactionsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BankTransactionsRepository::class)
 */
class BankTransactions
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $txn_date;

    /**
     * @ORM\Column(type="integer")
     */
    private $to_user;

    /**
     * @ORM\Column(type="integer")
     */
    private $from_user;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTxnDate(): ?\DateTimeInterface
    {
        return $this->txn_date;
    }

    public function setTxnDate(\DateTimeInterface $txn_date): self
    {
        $this->txn_date = $txn_date;

        return $this;
    }

    public function getToUser(): User
    {
        return $this->to_user;
    }

    public function setToUser(int $to_user): self
    {
        $this->to_user = $to_user;

        return $this;
    }

    public function getFromUser(): User
    {
        return $this->from_user;
    }

    public function setFromUser(int $from_user): self
    {
        $this->from_user = $from_user;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
