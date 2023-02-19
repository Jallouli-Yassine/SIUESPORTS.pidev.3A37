<?php

namespace App\Entity;

use App\Repository\TournoiRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TournoiRepository::class)]
class Tournoi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $nb_team = null;

    #[ORM\Column]
    private ?int $nb_joueur_team = null;

    #[ORM\OneToMany(mappedBy: 'idTournois', targetEntity: Classement::class)]
    private Collection $classements;

    #[ORM\Column(length: 255)]
    private ?string $nomtournoi = null;

    #[ORM\Column(length: 255)]
    private ?string $device = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $datestart = null;

    public function __construct()
    {
        $this->classements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbTeam(): ?int
    {
        return $this->nb_team;
    }

    public function setNbTeam(int $nb_team): self
    {
        $this->nb_team = $nb_team;

        return $this;
    }

    public function getNbJoueurTeam(): ?int
    {
        return $this->nb_joueur_team;
    }

    public function setNbJoueurTeam(int $nb_joueur_team): self
    {
        $this->nb_joueur_team = $nb_joueur_team;

        return $this;
    }

    /**
     * @return Collection<int, Classement>
     */
    public function getClassements(): Collection
    {
        return $this->classements;
    }

    public function addClassement(Classement $classement): self
    {
        if (!$this->classements->contains($classement)) {
            $this->classements->add($classement);
            $classement->setIdTournois($this);
        }

        return $this;
    }

    public function removeClassement(Classement $classement): self
    {
        if ($this->classements->removeElement($classement)) {
            // set the owning side to null (unless already changed)
            if ($classement->getIdTournois() === $this) {
                $classement->setIdTournois(null);
            }
        }

        return $this;
    }

    public function getNomtournoi(): ?string
    {
        return $this->nomtournoi;
    }

    public function setNomtournoi(string $nomtournoi): self
    {
        $this->nomtournoi = $nomtournoi;

        return $this;
    }

    public function getDevice(): ?string
    {
        return $this->device;
    }

    public function setDevice(string $device): self
    {
        $this->device = $device;

        return $this;
    }

    public function getDatestart(): ?\DateTimeImmutable
    {
        return $this->datestart;
    }

    public function setDatestart(\DateTimeImmutable $datestart): self
    {
        $this->datestart = $datestart;

        return $this;
    }
}
