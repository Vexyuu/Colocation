<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: FactureRepository::class)]
#[ApiResource]
class Facture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $typeOfCharge = null;

    #[ORM\Column]
    private ?float $totalAmount = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $billDate = null;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Appartement $appartment = null;

    /**
     * @var Collection<int, Quittance>
     */
    #[ORM\OneToMany(targetEntity: Quittance::class, mappedBy: 'billRef')]
    private Collection $quittances;

    public function __construct()
    {
        $this->quittances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeOfCharge(): ?string
    {
        return $this->typeOfCharge;
    }

    public function setTypeOfCharge(string $typeOfCharge): self
    {
        $this->typeOfCharge = $typeOfCharge;

        return $this;
    }

    public function getTotalAmount(): ?float
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(float $totalAmount): self
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    public function getBillDate(): ?\DateTime
    {
        return $this->billDate;
    }

    public function setBillDate(\DateTime $billDate): self
    {
        $this->billDate = $billDate;

        return $this;
    }

    public function getAppartment(): ?Appartement
    {
        return $this->appartment;
    }

    public function setAppartment(?Appartement $appartment): self
    {
        $this->appartment = $appartment;

        return $this;
    }

    /**
     * @return Collection<int, Quittance>
     */
    public function getQuittances(): Collection
    {
        return $this->quittances;
    }

    public function addQuittance(Quittance $quittance): self
    {
        if (!$this->quittances->contains($quittance)) {
            $this->quittances->add($quittance);
            $quittance->setBillRef($this);
        }

        return $this;
    }

    public function removeQuittance(Quittance $quittance): self
    {
        if ($this->quittances->removeElement($quittance)) {
            // set the owning side to null (unless already changed)
            if ($quittance->getBillRef() === $this) {
                $quittance->setBillRef(null);
            }
        }

        return $this;
    }
}
