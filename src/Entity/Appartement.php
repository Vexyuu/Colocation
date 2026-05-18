<?php

namespace App\Entity;

use App\Repository\AppartementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource; // 1. AJOUTE CETTE LIGNE

#[ORM\Entity(repositoryClass: AppartementRepository::class)]
#[ApiResource] 
class Appartement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $adresse = null;

    #[ORM\Column]
    private ?float $superficieTotale = null;

    #[ORM\ManyToOne(inversedBy: 'appartements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $landlord = null;

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

    public function getSuperficieTotale(): ?float
    {
        return $this->superficieTotale;
    }

    public function setSuperficieTotale(float $superficieTotale): static
    {
        $this->superficieTotale = $superficieTotale;

        return $this;
    }

    public function getLandlord(): ?User
    {
        return $this->landlord;
    }

    public function setLandlord(?User $landlord): static
    {
        $this->landlord = $landlord;

        return $this;
    }
}
