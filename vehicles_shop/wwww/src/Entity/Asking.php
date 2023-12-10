<?php

namespace App\Entity;

use App\Repository\AskingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AskingRepository::class)]
class Asking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?int $showcase_id = null;

    #[ORM\Column]
    #[Assert\Type('integer')]
    private ?int $programm_amount = null;

    #[ORM\Column]
    #[Assert\Type('float')]
    private ?float $rate = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?float $vehicle_price = null;

    #[ORM\Column]
    #[Assert\Type('integer')]
    private ?int $initial_payment = null;

    #[ORM\Column]
    #[Assert\Type('integer')]
    private ?int $monthly_payment = null;

    #[ORM\Column]
    #[Assert\Type('integer')]
    private ?int $credit_term = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    private ?int $created_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getShowcaseId(): ?int
    {
        return $this->showcase_id;
    }

    public function setShowcaseId(int $showcase_id): static
    {
        $this->showcase_id = $showcase_id;

        return $this;
    }

    public function getProgrammAmount(): ?int
    {
        return $this->programm_amount;
    }

    public function setProgrammAmount(int $programm_amount): static
    {
        $this->programm_amount = $programm_amount;

        return $this;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function setRate(float $rate): static
    {
        $this->rate = $rate;

        return $this;
    }

    public function getVehiclePrice(): ?float
    {
        return $this->vehicle_price;
    }

    public function setVehiclePrice(float $vehicle_price): static
    {
        $this->vehicle_price = $vehicle_price;

        return $this;
    }

    public function getInitialPayment(): ?int
    {
        return $this->initial_payment;
    }

    public function setInitialPayment(int $initial_payment): static
    {
        $this->initial_payment = $initial_payment;

        return $this;
    }

    public function getMonthlyPayment(): ?int
    {
        return $this->monthly_payment;
    }

    public function setMonthlyPayment(int $monthly_payment): static
    {
        $this->monthly_payment = $monthly_payment;

        return $this;
    }

    public function getCreditTerm(): ?int
    {
        return $this->credit_term;
    }

    public function setCreditTerm(int $credit_term): static
    {
        $this->credit_term = $credit_term;

        return $this;
    }

    public function getCreatedAt(): ?int
    {
        return $this->created_at;
    }

    public function setCreatedAt(int $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }
}
