<?php

namespace App\Entity;

use App\Repository\CandidatRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CandidatRepository::class)]
class Candidat
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  #[Groups(["getAnnonces"])]
  private ?int $id = null;

  #[ORM\Column(length: 255, nullable: true)]
  #[Groups(["getAnnonces"])]
  private ?string $cv = null;

  #[ORM\OneToMany(targetEntity: Candidature::class, mappedBy: 'candidat')]
  private Collection $candidatures;

  #[ORM\ManyToOne(inversedBy: 'candidats')]
  #[ORM\JoinColumn(nullable: false)]
  #[Groups(["getAnnonces"])]
  private ?User $candidat_user = null;

  #[ORM\ManyToOne(inversedBy: 'consultant_candidats')]
  private ?User $consultant = null;

  public function __construct()
  {
    $this->candidatures = new ArrayCollection();
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getCv(): ?string
  {
    return $this->cv;
  }

  public function setCv(?string $cv): static
  {
    $this->cv = $cv;

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
      $candidature->setCandidat($this);
    }

    return $this;
  }

  public function removeCandidature(Candidature $candidature): static
  {
    if ($this->candidatures->removeElement($candidature)) {
      // set the owning side to null (unless already changed)
      if ($candidature->getCandidat() === $this) {
        $candidature->setCandidat(null);
      }
    }

    return $this;
  }

  public function getCandidatUser(): ?User
  {
    return $this->candidat_user;
  }

  public function setCandidatUser(?User $candidat_user): static
  {
    $this->candidat_user = $candidat_user;

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
