<?php

namespace App\Entity;

use App\Repository\ClubRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClubRepository::class)]
class Club
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $stade;

    #[ORM\OneToOne(targetEntity: Logo::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $logo;

    #[ORM\Column(type: 'string', length: 255)]
    private $pelouse;

    #[ORM\Column(type: 'integer')]
    private $places;

    #[ORM\Column(type: 'string', length: 255)]
    private $couverture;

    #[ORM\OneToMany(mappedBy: 'team', targetEntity: Players::class)]
    private $players;

    public function __construct()
    {
        $this->players = new ArrayCollection();
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

    public function getStade(): ?string
    {
        return $this->stade;
    }

    public function setStade(?string $stade): self
    {
        $this->stade = $stade;

        return $this;
    }


    public function getLogo(): ?Logo
    {
        return $this->logo;
    }

    public function setLogo(Logo $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getPelouse(): ?string
    {
        return $this->pelouse;
    }

    public function setPelouse(string $pelouse): self
    {
        $this->pelouse = $pelouse;

        return $this;
    }

    public function getPlaces(): ?int
    {
        return $this->places;
    }

    public function setPlaces(int $places): self
    {
        $this->places = $places;

        return $this;
    }

    public function getCouverture(): ?string
    {
        return $this->couverture;
    }

    public function setCouverture(string $couverture): self
    {
        $this->couverture = $couverture;

        return $this;
    }

    /**
     * @return Collection<int, Players>
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Players $player): self
    {
        if (!$this->players->contains($player)) {
            $this->players[] = $player;
            $player->setTeam($this);
        }

        return $this;
    }

    public function removePlayer(Players $player): self
    {
        if ($this->players->removeElement($player)) {
            // set the owning side to null (unless already changed)
            if ($player->getTeam() === $this) {
                $player->setTeam(null);
            }
        }

        return $this;
    }


}
