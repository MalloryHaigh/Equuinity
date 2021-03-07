<?php

namespace App\Entity;

use App\Repository\BreedsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BreedsRepository::class)
 */
class Breeds
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
    private $ext_min;

    /**
     * @ORM\Column(type="integer")
     */
    private $ext_max;

    /**
     * @ORM\Column(type="integer")
     */
    private $ag_min;

    /**
     * @ORM\Column(type="integer")
     */
    private $ag_max;

    /**
     * @ORM\Column(type="integer")
     */
    private $grey_min;

    /**
     * @ORM\Column(type="integer")
     */
    private $grey_max;

    /**
     * @ORM\Column(type="integer")
     */
    private $cream_min;

    /**
     * @ORM\Column(type="integer")
     */
    private $cream_max;

    /**
     * @ORM\Column(type="integer")
     */
    private $pearl_min;

    /**
     * @ORM\Column(type="integer")
     */
    private $pearl_max;

    /**
     * @ORM\Column(type="integer")
     */
    private $dun_min;

    /**
     * @ORM\Column(type="integer")
     */
    private $dun_max;

    /**
     * @ORM\Column(type="integer")
     */
    private $champ_min;

    /**
     * @ORM\Column(type="integer")
     */
    private $champ_max;

    /**
     * @ORM\Column(type="integer")
     */
    private $flax_min;

    /**
     * @ORM\Column(type="integer")
     */
    private $flax_max;

    /**
     * @ORM\Column(type="integer")
     */
    private $silver_min;

    /**
     * @ORM\Column(type="integer")
     */
    private $silver_max;

    /**
     * @ORM\Column(type="integer")
     */
    private $rabicano_min;

    /**
     * @ORM\Column(type="integer")
     */
    private $rabicano_max;

    /**
     * @ORM\Column(type="integer")
     */
    private $roan_min;

    /**
     * @ORM\Column(type="integer")
     */
    private $roan_max;

    /**
     * @ORM\Column(type="integer")
     */
    private $sabino_min;

    /**
     * @ORM\Column(type="integer")
     */
    private $sabino_max;

    /**
     * @ORM\Column(type="integer")
     */
    private $white_min;

    /**
     * @ORM\Column(type="integer")
     */
    private $white_max;

    /**
     * @ORM\Column(type="integer")
     */
    private $tovero_min;

    /**
     * @ORM\Column(type="integer")
     */
    private $tovero_max;

    /**
     * @ORM\Column(type="integer")
     */
    private $overo_min;

    /**
     * @ORM\Column(type="integer")
     */
    private $overo_max;

    /**
     * @ORM\Column(type="integer")
     */
    private $splash_min;

    /**
     * @ORM\Column(type="integer")
     */
    private $splash_max;

    /**
     * @ORM\Column(type="integer")
     */
    private $leopard_min;

    /**
     * @ORM\Column(type="integer")
     */
    private $leopard_max;

    /**
     * @ORM\Column(type="integer")
     */
    private $pattern_min;

    /**
     * @ORM\Column(type="integer")
     */
    private $pattern_max;

    /**
     * @ORM\Column(type="integer")
     */
    private $brindle_min;

    /**
     * @ORM\Column(type="integer")
     */
    private $brindle_max;

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

    public function getExtMin(): ?int
    {
        return $this->ext_min;
    }

    public function setExtMin(int $ext_min): self
    {
        $this->ext_min = $ext_min;

        return $this;
    }

    public function getExtMax(): ?int
    {
        return $this->ext_max;
    }

    public function setExtMax(int $ext_max): self
    {
        $this->ext_max = $ext_max;

        return $this;
    }

    public function getAgMin(): ?int
    {
        return $this->ag_min;
    }

    public function setAgMin(int $ag_min): self
    {
        $this->ag_min = $ag_min;

        return $this;
    }

    public function getAgMax(): ?int
    {
        return $this->ag_max;
    }

    public function setAgMax(int $ag_max): self
    {
        $this->ag_max = $ag_max;

        return $this;
    }

    public function getGreyMin(): ?int
    {
        return $this->grey_min;
    }

    public function setGreyMin(int $grey_min): self
    {
        $this->grey_min = $grey_min;

        return $this;
    }

    public function getGreyMax(): ?int
    {
        return $this->grey_max;
    }

    public function setGreyMax(int $grey_max): self
    {
        $this->grey_max = $grey_max;

        return $this;
    }

    public function getCreamMin(): ?int
    {
        return $this->cream_min;
    }

    public function setCreamMin(int $cream_min): self
    {
        $this->cream_min = $cream_min;

        return $this;
    }

    public function getCreamMax(): ?int
    {
        return $this->cream_max;
    }

    public function setCreamMax(int $cream_max): self
    {
        $this->cream_max = $cream_max;

        return $this;
    }

    public function getPearlMin(): ?int
    {
        return $this->pearl_min;
    }

    public function setPearlMin(int $pearl_min): self
    {
        $this->pearl_min = $pearl_min;

        return $this;
    }

    public function getPearlMax(): ?int
    {
        return $this->pearl_max;
    }

    public function setPearlMax(int $pearl_max): self
    {
        $this->pearl_max = $pearl_max;

        return $this;
    }

    public function getDunMin(): ?int
    {
        return $this->dun_min;
    }

    public function setDunMin(int $dun_min): self
    {
        $this->dun_min = $dun_min;

        return $this;
    }

    public function getDunMax(): ?int
    {
        return $this->dun_max;
    }

    public function setDunMax(int $dun_max): self
    {
        $this->dun_max = $dun_max;

        return $this;
    }

    public function getChampMin(): ?int
    {
        return $this->champ_min;
    }

    public function setChampMin(int $champ_min): self
    {
        $this->champ_min = $champ_min;

        return $this;
    }

    public function getChampMax(): ?int
    {
        return $this->champ_max;
    }

    public function setChampMax(int $champ_max): self
    {
        $this->champ_max = $champ_max;

        return $this;
    }

    public function getFlaxMin(): ?int
    {
        return $this->flax_min;
    }

    public function setFlaxMin(int $flax_min): self
    {
        $this->flax_min = $flax_min;

        return $this;
    }

    public function getFlaxMax(): ?int
    {
        return $this->flax_max;
    }

    public function setFlaxMax(int $flax_max): self
    {
        $this->flax_max = $flax_max;

        return $this;
    }

    public function getSilverMin(): ?int
    {
        return $this->silver_min;
    }

    public function setSilverMin(int $silver_min): self
    {
        $this->silver_min = $silver_min;

        return $this;
    }

    public function getSilverMax(): ?int
    {
        return $this->silver_max;
    }

    public function setSilverMax(int $silver_max): self
    {
        $this->silver_max = $silver_max;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRabicanoMin()
    {
        return $this->rabicano_min;
    }

    /**
     * @param mixed $rabicano_min
     */
    public function setRabicanoMin($rabicano_min): void
    {
        $this->rabicano_min = $rabicano_min;
    }

    /**
     * @return mixed
     */
    public function getRabicanoMax()
    {
        return $this->rabicano_max;
    }

    /**
     * @param mixed $rabicano_max
     */
    public function setRabicanoMax($rabicano_max): void
    {
        $this->rabicano_max = $rabicano_max;
    }

    /**
     * @return mixed
     */
    public function getRoanMin()
    {
        return $this->roan_min;
    }

    /**
     * @param mixed $roan_min
     */
    public function setRoanMin($roan_min): void
    {
        $this->roan_min = $roan_min;
    }

    /**
     * @return mixed
     */
    public function getRoanMax()
    {
        return $this->roan_max;
    }

    /**
     * @param mixed $roan_max
     */
    public function setRoanMax($roan_max): void
    {
        $this->roan_max = $roan_max;
    }

    /**
     * @return mixed
     */
    public function getSabinoMin()
    {
        return $this->sabino_min;
    }

    /**
     * @param mixed $sabino_min
     */
    public function setSabinoMin($sabino_min): void
    {
        $this->sabino_min = $sabino_min;
    }

    /**
     * @return mixed
     */
    public function getSabinoMax()
    {
        return $this->sabino_max;
    }

    /**
     * @param mixed $sabino_max
     */
    public function setSabinoMax($sabino_max): void
    {
        $this->sabino_max = $sabino_max;
    }

    /**
     * @return mixed
     */
    public function getWhiteMin()
    {
        return $this->white_min;
    }

    /**
     * @param mixed $white_min
     */
    public function setWhiteMin($white_min): void
    {
        $this->white_min = $white_min;
    }

    /**
     * @return mixed
     */
    public function getWhiteMax()
    {
        return $this->white_max;
    }

    /**
     * @param mixed $white_max
     */
    public function setWhiteMax($white_max): void
    {
        $this->white_max = $white_max;
    }

    /**
     * @return mixed
     */
    public function getToveroMin()
    {
        return $this->tovero_min;
    }

    /**
     * @param mixed $tovero_min
     */
    public function setToveroMin($tovero_min): void
    {
        $this->tovero_min = $tovero_min;
    }

    /**
     * @return mixed
     */
    public function getToveroMax()
    {
        return $this->tovero_max;
    }

    /**
     * @param mixed $tovero_max
     */
    public function setToveroMax($tovero_max): void
    {
        $this->tovero_max = $tovero_max;
    }

    /**
     * @return mixed
     */
    public function getOveroMin()
    {
        return $this->overo_min;
    }

    /**
     * @param mixed $overo_min
     */
    public function setOveroMin($overo_min): void
    {
        $this->overo_min = $overo_min;
    }

    /**
     * @return mixed
     */
    public function getOveroMax()
    {
        return $this->overo_max;
    }

    /**
     * @param mixed $overo_max
     */
    public function setOveroMax($overo_max): void
    {
        $this->overo_max = $overo_max;
    }

    /**
     * @return mixed
     */
    public function getSplashMin()
    {
        return $this->splash_min;
    }

    /**
     * @param mixed $splash_min
     */
    public function setSplashMin($splash_min): void
    {
        $this->splash_min = $splash_min;
    }

    /**
     * @return mixed
     */
    public function getSplashMax()
    {
        return $this->splash_max;
    }

    /**
     * @param mixed $splash_max
     */
    public function setSplashMax($splash_max): void
    {
        $this->splash_max = $splash_max;
    }

    /**
     * @return mixed
     */
    public function getLeopardMin()
    {
        return $this->leopard_min;
    }

    /**
     * @param mixed $leopard_min
     */
    public function setLeopardMin($leopard_min): void
    {
        $this->leopard_min = $leopard_min;
    }

    /**
     * @return mixed
     */
    public function getLeopardMax()
    {
        return $this->leopard_max;
    }

    /**
     * @param mixed $leopard_max
     */
    public function setLeopardMax($leopard_max): void
    {
        $this->leopard_max = $leopard_max;
    }

    /**
     * @return mixed
     */
    public function getPatternMin()
    {
        return $this->pattern_min;
    }

    /**
     * @param mixed $pattern_min
     */
    public function setPatternMin($pattern_min): void
    {
        $this->pattern_min = $pattern_min;
    }

    /**
     * @return mixed
     */
    public function getPatternMax()
    {
        return $this->pattern_max;
    }

    /**
     * @param mixed $pattern_max
     */
    public function setPatternMax($pattern_max): void
    {
        $this->pattern_max = $pattern_max;
    }

    /**
     * @return mixed
     */
    public function getBrindleMin()
    {
        return $this->brindle_min;
    }

    /**
     * @param mixed $brindle_min
     */
    public function setBrindleMin($brindle_min): void
    {
        $this->brindle_min = $brindle_min;
    }

    /**
     * @return mixed
     */
    public function getBrindleMax()
    {
        return $this->brindle_max;
    }

    /**
     * @param mixed $brindle_max
     */
    public function setBrindleMax($brindle_max): void
    {
        $this->brindle_max = $brindle_max;
    }
}
