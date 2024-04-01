<?php

namespace App\Entity;

use App\Repository\AnnonceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AnnonceRepository::class)]
class Annonce
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getAnnonces"])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(["getAnnonces"])]
    private ?string $titre = null;

    #[ORM\Column(length: 20)]
    #[Groups(["getAnnonces"])]
    private ?string $typecontrat = null;

    #[ORM\ManyToOne(inversedBy: 'annonces')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["getAnnonces"])]
    private ?Poste $poste = null;

    #[ORM\Column(length: 60)]
    #[Groups(["getAnnonces"])]
    private ?string $ville = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(["getAnnonces"])]
    private ?\DateTimeInterface $datedebut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(["getAnnonces"])]
    private ?\DateTimeInterface $datefin = null;

    #[ORM\Column]
    #[Groups(["getAnnonces"])]
    private ?int $nombreheures = null;

    #[ORM\Column]
    #[Groups(["getAnnonces"])]
    private ?int $salaire = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(["getAnnonces"])]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(["getAnnonces"])]
    private ?\DateTimeInterface $dateajout = null;

    #[ORM\Column]
    #[Groups(["getAnnonces"])]
    private ?bool $validation = null;

    #[ORM\OneToMany(targetEntity: Candidature::class, mappedBy: 'annonce')]
    #[Groups(["getAnnonces"])]
    private Collection $candidatures;

    #[ORM\ManyToOne(inversedBy: 'annonces')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["getAnnonces"])]
    private ?Recruteur $recruteur = null;

    #[ORM\ManyToOne(inversedBy: 'annonces')]
    #[Groups(["getAnnonces"])]
    private ?User $consultant = null;

    public function __construct()
    {
        $this->candidatures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDateajout(): ?\DateTimeInterface
    {
        return $this->dateajout;
    }

    public function setDateajout(\DateTimeInterface $dateajout): static
    {
        $this->dateajout = $dateajout;

        return $this;
    }

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

    public function setDatedebut(\DateTimeInterface $datedebut): static
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    public function isValidation(): ?bool
    {
        return $this->validation;
    }

    public function setValidation(bool $validation): static
    {
        $this->validation = $validation;

        return $this;
    }

    public function getTypecontrat(): ?string
    {
        return $this->typecontrat;
    }

    public function setTypecontrat(string $typecontrat): static
    {
        $this->typecontrat = $typecontrat;

        return $this;
    }

    public function getNombreheures(): ?int
    {
        return $this->nombreheures;
    }

    public function setNombreheures(int $nombreheures): static
    {
        $this->nombreheures = $nombreheures;

        return $this;
    }

    public function getSalaire(): ?int
    {
        return $this->salaire;
    }

    public function setSalaire(int $salaire): static
    {
        $this->salaire = $salaire;

        return $this;
    }

    /**
     * @return Collection<int, Candidature>
     */
    public function getCandidatures(): Collection
    {
        return $this->candidatures;
    }

    public function addCandidature(Candidature $candidature): static
    {
        if (!$this->candidatures->contains($candidature)) {
            $this->candidatures->add($candidature);
            $candidature->setAnnonce($this);
        }

        return $this;
    }

    public function removeCandidature(Candidature $candidature): static
    {
        if ($this->candidatures->removeElement($candidature)) {
            // set the owning side to null (unless already changed)
            if ($candidature->getAnnonce() === $this) {
                $candidature->setAnnonce(null);
            }
        }

        return $this;
    }

    public function getRecruteur(): ?Recruteur
    {
        return $this->recruteur;
    }

    public function setRecruteur(?Recruteur $recruteur): static
    {
        $this->recruteur = $recruteur;

        return $this;
    }

    public function getConsultant(): ?User
    {
        return $this->consultant;
    }

    public function setConsultant(?User $consultant): static
    {
        $this->consultant = $consultant;

        return $this;
    }

    public function getPoste(): ?Poste
    {
        return $this->poste;
    }

    public function setPoste(?Poste $poste): static
    {
        $this->poste = $poste;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(?\DateTimeInterface $datefin): static
    {
        $this->datefin = $datefin;

        return $this;
    }
}
