<?php

namespace App\Entity;

use App\Enum\PaymentStatus;
use App\Repository\QuittanceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: QuittanceRepository::class)]
#[ApiResource]

class Quittance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $rentWithoutCharges = null;

    #[ORM\Column]
    private ?float $rentWithChargesPercent = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $transmissionDate = null;

    #[ORM\Column(enumType: PaymentStatus::class)]
    private ?PaymentStatus $paymentStatus = null;

    #[ORM\ManyToOne(inversedBy: 'quittances')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $tenant = null;

    #[ORM\ManyToOne(inversedBy: 'quittances')]
    private ?Facture $billRef = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRentWithoutCharges(): ?float
    {
        return $this->rentWithoutCharges;
    }

    public function setRentWithoutCharges(float $rentWithoutCharges): static
    {
        $this->rentWithoutCharges = $rentWithoutCharges;

        return $this;
    }

    public function getRentWithChargesPercent(): ?float
    {
        return $this->rentWithChargesPercent;
    }

    public function setRentWithChargesPercent(float $rentWithChargesPercent): static
    {
        $this->rentWithChargesPercent = $rentWithChargesPercent;

        return $this;
    }

    public function getTransmissionDate(): ?\DateTime
    {
        return $this->transmissionDate;
    }

    public function setTransmissionDate(\DateTime $transmissionDate): static
    {
        $this->transmissionDate = $transmissionDate;

        return $this;
    }

    public function getPaymentStatus(): ?PaymentStatus
    {
        return $this->paymentStatus;
    }

    public function setPaymentStatus(PaymentStatus $paymentStatus): static
    {
        $this->paymentStatus = $paymentStatus;

        return $this;
    }

    public function getTenant(): ?User
    {
        return $this->tenant;
    }

    public function setTenant(?User $tenant): static
    {
        $this->tenant = $tenant;

        return $this;
    }

    public function getBillRef(): ?Facture
    {
        return $this->billRef;
    }

    public function setBillRef(?Facture $billRef): static
    {
        $this->billRef = $billRef;

        return $this;
    }
}
