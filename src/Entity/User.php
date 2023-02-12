<?php

namespace App\Entity;

use App\Repository\Entity\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo_url = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_naissance = null;

    /**
     * ManyToMany relation User----Jeux
     * #[ORM\ManyToMany(targetEntity: Jeux::class, inversedBy: 'users')]
     * #[ORM\JoinTable(name:'reviewuserjeux')]
     * private Collection $jeux;
     */


    #[ORM\Column]
    private ?float $point = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: CommentaireNews::class)]
    private Collection $commentaireNews;

    public function __construct()
    {
        $this->commentaireNews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getPhotoUrl(): ?string
    {
        return $this->photo_url;
    }

    public function setPhotoUrl(?string $photo_url): self
    {
        $this->photo_url = $photo_url;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->date_naissance;
    }

    public function setDateNaissance(\DateTimeInterface $date_naissance): self
    {
        $this->date_naissance = $date_naissance;

        return $this;
    }

    public function getPoint(): ?float
    {
        return $this->point;
    }

    public function setPoint(float $point): self
    {
        $this->point = $point;

        return $this;
    }

    /**
     * @return Collection<int, CommentaireNews>
     */
    public function getCommentaireNews(): Collection
    {
        return $this->commentaireNews;
    }

    public function addCommentaireNews(CommentaireNews $commentaireNews): self
    {
        if (!$this->commentaireNews->contains($commentaireNews)) {
            $this->commentaireNews->add($commentaireNews);
            $commentaireNews->setUser($this);
        }

        return $this;
    }

    public function removeCommentaireNews(CommentaireNews $commentaireNews): self
    {
        if ($this->commentaireNews->removeElement($commentaireNews)) {
            // set the owning side to null (unless already changed)
            if ($commentaireNews->getUser() === $this) {
                $commentaireNews->setUser(null);
            }
        }

        return $this;
    }

}
