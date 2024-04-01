<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation\Groups;
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  #[Groups(["getAnnonces"])]
  private ?int $id = null;

  #[ORM\Column(length: 180)]
  private ?string $email = null;

  /**
   * @var list<string> The user roles
   */
  #[ORM\Column]
  private array $roles = [];

  /**
   * @var string The hashed password
   * @Assert\Length(min=8, max=255, minMessage="Au moins 8 caractères")
   */
  #[ORM\Column]
  private ?string $password = null;

  /**
   * @var string The hashed password
   * @Assert\EqualTo(propertyPath="password", message="Les 2 mots de passe doivent être identiques." )
   */
  private $password_confirm;

  #[ORM\Column(length: 50)]
  #[Groups(["getAnnonces"])]
  private ?string $nom = null;

  #[ORM\Column(length: 50)]
  #[Groups(["getAnnonces"])]
  private ?string $prenom = null;

  #[ORM\Column(length: 40)]
  private ?string $role = null;
  
  #[ORM\OneToMany(targetEntity: Candidature::class, mappedBy: 'consultant_approval')]
  private Collection $candidatures;

  #[ORM\OneToMany(targetEntity: Annonce::class, mappedBy: 'consultant')]
  private Collection $annonces;

  #[ORM\OneToMany(targetEntity: Recruteur::class, mappedBy: 'recruteur_user')]
  private Collection $recruteurs;

  #[ORM\OneToMany(targetEntity: Candidat::class, mappedBy: 'candidat_user')]
  private Collection $candidats;

  #[ORM\OneToMany(targetEntity: Candidat::class, mappedBy: 'consultant')]
  private Collection $consultant_candidats;

  #[ORM\OneToMany(targetEntity: Recruteur::class, mappedBy: 'consultant')]
  private Collection $consultant_recruteurs;

  public function __construct()
  {
      $this->candidats = new ArrayCollection();
      $this->candidatures = new ArrayCollection();
      $this->annonces = new ArrayCollection();
      $this->recruteurs = new ArrayCollection();
      $this->consultant_candidats = new ArrayCollection();
      $this->consultant_recruteurs = new ArrayCollection();
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getEmail(): ?string
  {
    return $this->email;
  }

  public function setEmail(string $email): static
  {
    $this->email = $email;

    return $this;
  }

  /**
   * A visual identifier that represents this user.
   *
   * @see UserInterface
   */
  public function getUserIdentifier(): string
  {
    return (string) $this->email;
  }

  /**
   * @see UserInterface
   *
   * @return list<string>
   */
  public function getRoles(): array
  {
    $roles = $this->roles;
    // guarantee every user at least has ROLE_USER
    //$roles[] = '';

    return array_unique($roles);
  }

  /**
   * @param list<string> $roles
   */
  public function setRoles(array $roles): static
  {
    $this->roles = $roles;

    return $this;
  }

  /**
   * @see PasswordAuthenticatedUserInterface
   */
  public function getPassword(): string
  {
    return $this->password;
  }

  public function setPassword(string $password): static
  {
    $this->password = $password;

    return $this;
  }

  public function getPasswordConfirm(): ?string
  {
    return $this->password_confirm;
  }

  public function setPasswordConfirm(string $password): self
  {
    $this->password_confirm = $password;

    return $this;
  }

  /**
   * @see UserInterface
   */
  public function eraseCredentials(): void
  {
    // If you store any temporary, sensitive data on the user, clear it here
    // $this->plainPassword = null;
  }

  public function getNom(): ?string
  {
    return $this->nom;
  }

  public function setNom(string $nom): static
  {
    $this->nom = $nom;

    return $this;
  }

  public function getPrenom(): ?string
  {
    return $this->prenom;
  }

  public function setPrenom(string $prenom): static
  {
    $this->prenom = $prenom;

    return $this;
  }

  public function getRole(): ?string
  {
    return $this->role;
  }

  public function setRole(string $role): static
  {
    $this->role = $role;

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
          $candidature->setConsultantApproval($this);
      }

      return $this;
  }

  public function removeCandidature(Candidature $candidature): static
  {
      if ($this->candidatures->removeElement($candidature)) {
          // set the owning side to null (unless already changed)
          if ($candidature->getConsultantApproval() === $this) {
              $candidature->setConsultantApproval(null);
          }
      }

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
          $annonce->setConsultant($this);
      }

      return $this;
  }

  public function removeAnnonce(Annonce $annonce): static
  {
      if ($this->annonces->removeElement($annonce)) {
          // set the owning side to null (unless already changed)
          if ($annonce->getConsultant() === $this) {
              $annonce->setConsultant(null);
          }
      }

      return $this;
  }

  /**
   * @return Collection<int, Candidat>
   */
  public function getCandidats(): Collection
  {
      return $this->candidats;
  }

  public function addCandidat(Candidat $candidat): static
  {
      if (!$this->candidats->contains($candidat)) {
          $this->candidats->add($candidat);
          $candidat->setCandidatUser($this);
      }

      return $this;
  }

  public function removeCandidat(Candidat $candidat): static
  {
      if ($this->candidats->removeElement($candidat)) {
          // set the owning side to null (unless already changed)
          if ($candidat->getCandidatUser() === $this) {
              $candidat->setCandidatUser(null);
          }
      }

      return $this;
  }

  /**
   * @return Collection<int, Candidat>
   */
  public function getConsultantCandidats(): Collection
  {
      return $this->consultant_candidats;
  }

  public function addConsultantCandidat(Candidat $consultantCandidat): static
  {
      if (!$this->consultant_candidats->contains($consultantCandidat)) {
          $this->consultant_candidats->add($consultantCandidat);
          $consultantCandidat->setConsultant($this);
      }

      return $this;
  }

  public function removeConsultantCandidat(Candidat $consultantCandidat): static
  {
      if ($this->consultant_candidats->removeElement($consultantCandidat)) {
          // set the owning side to null (unless already changed)
          if ($consultantCandidat->getConsultant() === $this) {
              $consultantCandidat->setConsultant(null);
          }
      }

      return $this;
  }

  /**
   * @return Collection<int, Recruteur>
   */
  public function getConsultantRecruteurs(): Collection
  {
      return $this->consultant_recruteurs;
  }

  public function addConsultantRecruteur(Recruteur $consultantRecruteur): static
  {
      if (!$this->consultant_recruteurs->contains($consultantRecruteur)) {
          $this->consultant_recruteurs->add($consultantRecruteur);
          $consultantRecruteur->setConsultant($this);
      }

      return $this;
  }

  public function removeConsultantRecruteur(Recruteur $consultantRecruteur): static
  {
      if ($this->consultant_recruteurs->removeElement($consultantRecruteur)) {
          // set the owning side to null (unless already changed)
          if ($consultantRecruteur->getConsultant() === $this) {
              $consultantRecruteur->setConsultant(null);
          }
      }

      return $this;
  }

  /**
   * @return Collection<int, Recruteur>
   */
  public function getRecruteurs(): Collection
  {
    return $this->recruteurs;
  }

  public function addRecruteur(Recruteur $recruteur): static
  {
    if (!$this->recruteurs->contains($recruteur)) {
      $this->recruteurs->add($recruteur);
      $recruteur->setRecruteurUser($this);
    }

    return $this;
  }

  public function removeRecruteur(Recruteur $recruteur): static
  {
    if ($this->recruteurs->removeElement($recruteur)) {
      // set the owning side to null (unless already changed)
      if ($recruteur->getRecruteurUser() === $this) {
        $recruteur->setRecruteurUser(null);
      }
    }

    return $this;
  }
}
