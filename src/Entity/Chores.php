<?php

namespace App\Entity;

use App\Enum\ChoresStatus;
use App\Repository\ChoresRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: ChoresRepository::class)]
#[ApiResource]
class Chores
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $dayOfTheWeek = null;

    #[ORM\Column(enumType: ChoresStatus::class)]
    private ?ChoresStatus $choreStatus = null;

    #[ORM\ManyToOne(inversedBy: 'chores')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Appartement $appartment = null;

    #[ORM\ManyToOne(inversedBy: 'chores')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $assignedTo = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDayOfTheWeek(): ?string
    {
        return $this->dayOfTheWeek;
    }

    public function setDayOfTheWeek(string $dayOfTheWeek): self
    {
        $this->dayOfTheWeek = $dayOfTheWeek;

        return $this;
    }

    public function getChoreStatus(): ?ChoresStatus
    {
        return $this->choreStatus;
    }

    public function setChoreStatus(ChoresStatus $choreStatus): self
    {
        $this->choreStatus = $choreStatus;

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

    public function getAssignedTo(): ?User
    {
        return $this->assignedTo;
    }

    public function setAssignedTo(?User $assignedTo): self
    {
        $this->assignedTo = $assignedTo;

        return $this;
    }
}
