<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StatRmAnneeRepository")
 */
class StatRmAnnee
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
     * @ORM\Column(type="float")
     */
    private $commission;

    /**
     * @ORM\Column(type="float")
     */
    private $forfait;

    /**
     * @ORM\Column(type="float")
     */
    private $ca_total;

    /**
     * @ORM\Column(type="float")
     */
    private $ca_variable;

    /**
     * @ORM\Column(type="float")
     */
    private $ca_forfait;

    /**
     * @ORM\Column(type="float")
     */
    private $mode_livraison;

    /**
     * @ORM\Column(type="float")
     */
    private $mode_ae;

    /**
     * @ORM\Column(type="float")
     */
    private $commande_midi;

    /**
     * @ORM\Column(type="float")
     */
    private $commande_soir;

    /**
     * @ORM\Column(type="float")
     */
    private $support_rm;

    /**
     * @ORM\Column(type="float")
     */
    private $support_smart;

    /**
     * @ORM\Column(type="float")
     */
    private $support_vit;

    /**
     * @ORM\Column(type="float")
     */
    private $support_autre;

    /**
     * @ORM\Column(type="float")
     */
    private $client_connecte;

    /**
     * @ORM\Column(type="float")
     */
    private $client_express;

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

    public function getCommission(): ?float
    {
        return $this->commission;
    }

    public function setCommission(float $commission): self
    {
        $this->commission = $commission;

        return $this;
    }

    public function getForfait(): ?float
    {
        return $this->forfait;
    }

    public function setForfait(float $forfait): self
    {
        $this->forfait = $forfait;

        return $this;
    }

    public function getCaTotal(): ?float
    {
        return $this->ca_total;
    }

    public function setCaTotal(float $ca_total): self
    {
        $this->ca_total = $ca_total;

        return $this;
    }

    public function getCaVariable(): ?float
    {
        return $this->ca_variable;
    }

    public function setCaVariable(float $ca_variable): self
    {
        $this->ca_variable = $ca_variable;

        return $this;
    }

    public function getCaForfait(): ?float
    {
        return $this->ca_forfait;
    }

    public function setCaForfait(float $ca_forfait): self
    {
        $this->ca_forfait = $ca_forfait;

        return $this;
    }

    public function getModeLivraison(): ?float
    {
        return $this->mode_livraison;
    }

    public function setModeLivraison(float $mode_livraison): self
    {
        $this->mode_livraison = $mode_livraison;

        return $this;
    }

    public function getModeAe(): ?float
    {
        return $this->mode_ae;
    }

    public function setModeAe(float $mode_ae): self
    {
        $this->mode_ae = $mode_ae;

        return $this;
    }

    public function getCommandeMidi(): ?float
    {
        return $this->commande_midi;
    }

    public function setCommandeMidi(float $commande_midi): self
    {
        $this->commande_midi = $commande_midi;

        return $this;
    }

    public function getCommandeSoir(): ?float
    {
        return $this->commande_soir;
    }

    public function setCommandeSoir(float $commande_soir): self
    {
        $this->commande_soir = $commande_soir;

        return $this;
    }

    public function getSupportRm(): ?float
    {
        return $this->support_rm;
    }

    public function setSupportRm(float $support_rm): self
    {
        $this->support_rm = $support_rm;

        return $this;
    }

    public function getSupportSmart(): ?float
    {
        return $this->support_smart;
    }

    public function setSupportSmart(float $support_smart): self
    {
        $this->support_smart = $support_smart;

        return $this;
    }

    public function getSupportVit(): ?float
    {
        return $this->support_vit;
    }

    public function setSupportVit(float $support_vit): self
    {
        $this->support_vit = $support_vit;

        return $this;
    }

    public function getSupportAutre(): ?float
    {
        return $this->support_autre;
    }

    public function setSupportAutre(float $support_autre): self
    {
        $this->support_autre = $support_autre;

        return $this;
    }

    public function getClientConnecte(): ?float
    {
        return $this->client_connecte;
    }

    public function setClientConnecte(float $client_connecte): self
    {
        $this->client_connecte = $client_connecte;

        return $this;
    }

    public function getClientExpress(): ?float
    {
        return $this->client_express;
    }

    public function setClientExpress(float $client_express): self
    {
        $this->client_express = $client_express;

        return $this;
    }
}
