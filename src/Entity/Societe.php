<?php

namespace App\Entity;

use App\Repository\SocieteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SocieteRepository::class)]
class Societe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La société ne peut pas être vide")]
    private ?string $societe = null;
    
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'adresse ne peut pas être vide")]
    private ?string $adresse = null;
    
    #[ORM\Column(type: 'string', length: 8)]
    #[Assert\NotBlank(message: "Numdetel ne peut pas être vide")]
    #[Assert\Regex(
        pattern: "/^\d{8}$/",
        message: "Le numéro de téléphone doit contenir exactement 8 chiffres"
    )]
    #[Assert\Regex(
        pattern: "/[^\d]+/",
        match: false,
        message: "Le numéro de téléphone ne peut contenir que des chiffres"
    )]
    private ?string $numdetel = null;
    
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Email ne peut pas être vide")]
    #[Assert\Regex(
        pattern: "/@gmail\.com$/i",
        message: "L'adresse email doit se terminer par '@gmail.com'"
    )]
    #[Assert\Email(message: "Format d'email invalide")]
    
    private ?string $email = null;

    
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le statut juridique ne peut pas être vide")]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z]+$/",
        message: "Le statut juridique ne peut contenir que des lettres."
    )]
    private ?string $statutjuridique = null;
    
    #[ORM\OneToMany(targetEntity: Fournisseur::class, mappedBy: 'societe')]
    private Collection $fournisseurs;

    public function __construct()
    {
        $this->fournisseurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSociete(): ?string
    {
        return $this->societe;
    }

    public function setSociete(string $societe): static
    {
        $this->societe = $societe;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getNumdetel(): ?string
    {
        return $this->numdetel;
    }

    public function setNumdetel(string $numdetel): static
    {
        $this->numdetel = $numdetel;

        return $this;
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

    public function getStatutjuridique(): ?string
    {
        return $this->statutjuridique;
    }

    public function setStatutjuridique(string $statutjuridique): static
    {
        $this->statutjuridique = $statutjuridique;

        return $this;
    }

    /**
     * @return Collection<int, Fournisseur>
     */
    public function getFournisseurs(): Collection
    {
        return $this->fournisseurs;
    }

    public function addFournisseur(Fournisseur $fournisseur): static
    {
        if (!$this->fournisseurs->contains($fournisseur)) {
            $this->fournisseurs->add($fournisseur);
            $fournisseur->setSociete($this);
        }

        return $this;
    }

    public function removeFournisseur(Fournisseur $fournisseur): static
    {
        if ($this->fournisseurs->removeElement($fournisseur)) {
            // set the owning side to null (unless already changed)
            if ($fournisseur->getSociete() === $this) {
                $fournisseur->setSociete(null);
            }
        }

        return $this;
    }
    
    public function __toString(): string
    {
        return $this->societe; 
    }
}

