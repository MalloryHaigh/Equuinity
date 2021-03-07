<?php

namespace App\Entity;

use App\Repository\HorsesRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HorsesRepository::class)
 */
class Horses
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
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $active;

    /**
     * @ORM\Column(type="integer")
     */
    private $age;

    /**
     * @ORM\ManyToOne(targetEntity="Breeds")
     */
    private Breeds $breed;

    /**
     * @ORM\Column(type="date")
     */
    private $birthday;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $birth_day_of_week;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $genotype;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phenotype;

    /**
     * @ORM\Column(type="integer")
     */
    private $points;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    private User $owner;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    private User $breeder;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gender;

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     */
    public function setGender($gender): void
    {
        $this->gender = $gender;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Breeds
     */
    public function getBreed(): Breeds
    {
        return $this->breed;
    }

    /**
     * @param Breeds $breed
     */
    public function setBreed(Breeds $breed): void
    {
        $this->breed = $breed;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getBirthDayOfWeek(): ?string
    {
        return $this->birth_day_of_week;
    }

    public function setBirthDayOfWeek(string $birth_day_of_week): self
    {
        $this->birth_day_of_week = $birth_day_of_week;

        return $this;
    }

    public function getGenotype(): ?string
    {
        return $this->genotype;
    }

    public function setGenotype(string $genotype): self
    {
        $this->genotype = $genotype;

        return $this;
    }

    public function getPhenotype(): ?string
    {
        return $this->phenotype;
    }

    public function setPhenotype(string $phenotype): self
    {
        $this->phenotype = $phenotype;

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(int $points): self
    {
        $this->points = $points;

        return $this;
    }

    /**
     * @return User
     */
    public function getOwner(): User
    {
        return $this->owner;
    }

    /**
     * @param User $owner
     */
    public function setOwner(User $owner): void
    {
        $this->owner = $owner;
    }

    /**
     * @return User
     */
    public function getBreeder(): User
    {
        return $this->breeder;
    }

    /**
     * @param User $breeder
     */
    public function setBreeder(User $breeder): void
    {
        $this->breeder = $breeder;
    }

    /**
     * @return int
     */
    public function getActive(): int
    {
        return $this->active;
    }

    /**
     * @param int $active
     */
    public function setActive(int $active): void
    {
        $this->active = $active;
    }

    /**
     * @return int
     */
    public function getAge(): int
    {
        return $this->age;
    }

    /**
     * @param int $age
     */
    public function setAge(int $age): void
    {
        $this->age = $age;
    }


    /**
     * Horse constructor.
     */
    public function __construct()
    {

        $now = date('Y-m-d H:i:s');
        $this->birthday = new DateTime($now);

        $dow = date('l');

        $this->birth_day_of_week = $dow;

        $this->name = "New Horse";
        $this->points = 0;
        $this->active = 1;

        $this->age = 0;

    }
}
