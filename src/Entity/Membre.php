<?php

namespace App\Entity;

use App\Repository\MembreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MembreRepository::class)]
class Membre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $point = null;

    #[ORM\ManyToOne(inversedBy: 'membres')]
    private ?Gamer $idGamer = null;

    #[ORM\ManyToOne(inversedBy: 'membres')]
    private ?Team $idTeam = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPoint(): ?int
    {
        return $this->point;
    }

    public function setPoint(int $point): self
    {
        $this->point = $point;

        return $this;
    }

    public function getIdGamer(): ?Gamer
    {
        return $this->idGamer;
    }

    public function setIdGamer(?Gamer $idGamer): self
    {
        $this->idGamer = $idGamer;

        return $this;
    }

    public function getIdTeam(): ?Team
    {
        return $this->idTeam;
    }

    public function setIdTeam(?Team $idTeam): self
    {
        $this->idTeam = $idTeam;

        return $this;
    }
}
