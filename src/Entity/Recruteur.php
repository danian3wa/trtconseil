<?php

namespace App\Entity;

use App\Repository\RecruteurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RecruteurRepository::class)]
class Recruteur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getAnnonces"])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getAnnonces"])]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getAnnonces"])]
    private ?string $adresse = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["getAnnonces"])]
    private ?int $code_postal = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getAnnonces"])]
    private ?string $ville = null;

    #[ORM\OneToMany(targetEntity: Annonce::class, mappedBy: 'recruteur')]
    private Collection $annonces;

    #[ORM\ManyToOne(inversedBy: 'recruteurs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $recruteur_user = null;

    #[ORM\ManyToOne(inversedBy: 'consultant_recruteurs')]
    private ?User $consultant = null;
/*
    #[ORM\ManyToOne(inversedBy: 'recruteurs')]
    private ?User $consultant = null;
*/
    public function __construct()
    {
        $this->annonces = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodePostal(): ?int
    {
        return $this->code_postal;
    }

    public function setCodePostal(?int $code_postal): static
    {
        $this->code_postal = $code_postal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * @return Collection<int, Annonce>
     */
    public function getAnnonces(): Collection
    {
        return $this->annonces;
    }

    public function addAnnonce(Annonce $annonce): static
    {
        if (!$this->annonces->contains($annonce)) {
            $this->annonces->add($annonce);
            $annonce->setRecruteur($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonce $annonce): static
    {
        if ($this->annonces->removeElement($annonce)) {
            // set the owning side to null (unless already changed)
            if ($annonce->getRecruteur() === $this) {
                $annonce->setRecruteur(null);
            }
        }

        return $this;
    }

    public function getRecruteurUser(): ?User
    {
        return $this->recruteur_user;
    }

    public function setRecruteurUser(?User $recruteur_user): static
    {
        $this->recruteur_user = $recruteur_user;

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

}
