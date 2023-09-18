<?php

namespace App\Entity;

class Guest
{
    private $firstName;

    private $lastName;

    private $companyName;

    private $email;

    private $paymentStatus;

    private $arrived;

    private $receivedPkg;

    private $receivedCert;

    private $notes;

    private $id;
    
    public function getFullName(): ?string
    {
        return trim("{$this->getFirstName()} {$this->getLastName()}");
    }
    
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(?string $companyName): self
    {
        $this->companyName = $companyName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPaymentStatus(): ?int
    {
        return $this->paymentStatus;
    }

    public function setPaymentStatus(int $paymentStatus): self
    {
        $this->paymentStatus = $paymentStatus;

        return $this;
    }

    public function getArrived(): ?bool
    {
        return $this->arrived;
    }

    public function setArrived(bool $arrived): self
    {
        $this->arrived = $arrived;

        return $this;
    }

    public function getReceivedPkg(): ?int
    {
        return $this->receivedPkg;
    }

    public function setReceivedPkg(int $receivedPkg): self
    {
        $this->receivedPkg = $receivedPkg;

        return $this;
    }

    public function getReceivedCert(): ?int
    {
        return $this->receivedCert;
    }

    public function setReceivedCert(int $receivedCert): self
    {
        $this->receivedCert = $receivedCert;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
