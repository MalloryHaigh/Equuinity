<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=180,options={"default":"New Player"}, nullable=true)
     */
    private $displayName;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string")
     */

    private $email;

    /**
     * @ORM\Column(type="text",nullable=true)
     */

    private $profile;

    /**
     * @ORM\Column(type="text",nullable=true)
     */

    private $signature;

    /**
     * @return mixed
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * @param mixed $signature
     */
    public function setSignature($signature): void
    {
        $this->signature = $signature;
    }

    /**
     * @ORM\Column(type="float",options={"default":50000})
     */

    private int $money;

    /**
     * @ORM\Column(type="float",options={"default":0})
     */

    private $bank;

    /**
     * @ORM\Column(type="integer",options={"default":3000})
     */

    private int $salary;

    /**
     * @ORM\Column(type="integer",options={"default":0})
     */

    private int $credits = 0;

    /**
     * @ORM\Column(type="string", length=225,nullable=true)
     */

    private $avatar;

    /**
 * @ORM\Column(type="string", length=225,nullable=true,options={"default":"N00b."})
 */

    private $status;

    /**
     * @ORM\Column(type="string", length=225,nullable=true,options={"default":"New Stable"})
     */

    private $stable;

    /**
     * @ORM\Column(type="integer",options={"default":0})
     */

    private int $stalls = 0;

    /**
     * @ORM\Column(type="integer",options={"default":0})
     */

    private int $board = 0;

    /**
     * @ORM\Column(type="integer",options={"default":1})
     */

    private int $shows = 1;

    /**
     * @return int
     */
    public function getShows(): int
    {
        return $this->shows;
    }

    /**
     * @param int $shows
     */
    public function setShows(int $shows): void
    {
        $this->shows = $shows;
    }

    /**
     * @return int
     */
    public function getBoard(): int
    {
        return $this->board;
    }

    /**
     * @param int $board
     */
    public function setBoard(int $board): void
    {
        $this->board = $board;
    }

    /**
     * @return int
     */
    public function getStalls(): int
    {
        return $this->stalls;
    }

    /**
     * @param int $stalls
     */
    public function setStalls(int $stalls): void
    {
        $this->stalls = $stalls;
    }


    /**
     * @return string
     */
    public function getStable(): string
    {
        return $this->stable;
    }

    /**
     * @param string $stable
     */
    public function setStable(string $stable): void
    {
        $this->stable = $stable;
    }

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */

    private $joined;

    /**
     * @ORM\Column(type="integer",options={"default":0})
     */

    private int $days = 0;

    /**
     * @ORM\Column(type="float",options={"default":0.15})
     */

    private float $interest = 0.15;

    /**
     * @return float
     */
    public function getInterest(): float
    {
        return $this->interest;
    }

    /**
     * @param float $interest
     */
    public function setInterest(float $interest): void
    {
        $this->interest = $interest;
    }

    /**
     * @return int
     */
    public function getDays(): int
    {
        return $this->days;
    }

    /**
     * @param int $days
     */
    public function setDays(int $days): void
    {
        $this->days = $days;
    }

    /**
     * @return mixed
     */
    public function getJoined()
    {
        return $this->joined;
    }

    /**
     * @param mixed $joined
     */
    public function setJoined($joined): void
    {
        $this->joined = $joined;
    }

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

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param mixed $avatar
     */
    public function setAvatar($avatar): void
    {
        $this->avatar = $avatar;
    }

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->money = 50_000;
        $this->credits = 0;
        $this->days = 0;
        $this->displayName = "New Player";
        $this->joined = new DateTime();
        $this->stable = "New Stable";
        $this->stalls = 0;
        $this->board = 0;
        $this->bank = 0;
        $this->salary = 3000;
        $this->interest = 0.15;
        $this->avatar = 'https://i.ibb.co/8m6KdH6/default.png';
        $this->shows = 5;
    }

    /**
     * @return mixed
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * @param mixed $displayName
     */
    public function setDisplayName($displayName): void
    {
        $this->displayName = $displayName;
    }

    /**
     * @return mixed
     */
    public function getCredits()
    {
        return $this->credits;
    }

    /**
     * @param mixed $credits
     */
    public function setCredits($credits): void
    {
        $this->credits = $credits;
    }

    /**
     * @return mixed
     */
    public function getMoney() : float
    {
        return $this->money;
    }

    /**
     * @param mixed $money
     */
    public function setMoney(float $money): void
    {
        $this->money = $money;
    }



    /**
     * @return mixed
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @param mixed $profile
     */
    public function setProfile($profile): void
    {
        $this->profile = $profile;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getDisplayName()." (#".$this->getId().")";
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }


    public function getBank(): ?float
    {
        return $this->bank;
    }


    public function setBank(float $bank): self
    {
         $this->bank = $bank;

         return $this;
    }

    /**
     * @return int
     */
    public function getSalary(): int
    {
        return $this->salary;
    }

    /**
     * @param int $salary
     */
    public function setSalary(int $salary): void
    {
        $this->salary = $salary;
    }
}
