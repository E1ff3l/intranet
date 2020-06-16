<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InfoDiverseRepository")
 */
class InfoDiverse
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
    private $numLastDevis;

    /**
     * @ORM\Column(type="integer")
     */
    private $numLastFacture;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumLastDevis(): ?int
    {
        return $this->numLastDevis;
    }

    public function setNumLastDevis(int $numLastDevis): self
    {
        $this->numLastDevis = $numLastDevis;

        return $this;
    }

    public function getNumLastFacture(): ?int
    {
        return $this->numLastFacture;
    }

    public function setNumLastFacture(int $numLastFacture): self
    {
        $this->numLastFacture = $numLastFacture;

        return $this;
    }
}
