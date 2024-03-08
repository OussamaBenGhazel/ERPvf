<?php

namespace App\Entity;

use App\Repository\FournisseurRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FournisseurRepository::class)]
class Fournisseur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom ne peut pas être vide")]
    #[Assert\Type(type: "string", message: "Nom devrait être une chaîne")]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z\s]+$/",
        message: "Ce champ doit contenir uniquement des lettres et des espaces"
    )]
    private ?string $nom = null;

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

    #[ORM\ManyToOne(inversedBy: 'fournisseurs', fetch: 'EAGER')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Societe $societe = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSociete(): ?Societe
    {
        return $this->societe;
    }

    public function setSociete(?Societe $societe): static
    {
        $this->societe = $societe;

        return $this;
    }

}