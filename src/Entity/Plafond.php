<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlafondRepository")
 */
class Plafond
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $annee;

    /**
     * @ORM\Column(type="integer")
     */
    private $limiteCA;

    /**
     * @ORM\Column(type="integer")
     */
    private $tolerance;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    public function getLimiteCA(): ?int
    {
        return $this->limiteCA;
    }

    public function setLimiteCA(int $limiteCA): self
    {
        $this->limiteCA = $limiteCA;

        return $this;
    }

    public function getTolerance(): ?int
    {
        return $this->tolerance;
    }

    public function setTolerance(int $tolerance): self
    {
        $this->tolerance = $tolerance;

        return $this;
    }
}
