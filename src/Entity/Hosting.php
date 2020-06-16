<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\GreaterThan;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HostingRepository")
 */
class Hosting
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="hostings")
     */
    private $client;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank( message = "Ce champ est obligatoire!" )
     */
    private $site;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank( message = "Ce champ est obligatoire!" )
     * @Assert\Regex(
     *     pattern="/^\d+/",
     *     message="La valeur n'est pas correcte!"
     * )
     */
    private $prix;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank( message = "Ce champ est obligatoire!" )
     */
    private $debut;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank( message = "Ce champ est obligatoire!" )
     * @Assert\GreaterThan( 
     *      propertyPath= "debut",
     *      message = "La date de fin n'est pas correcte!" 
     * )
     */
    private $fin;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getSite(): ?string
    {
        return $this->site;
    }

    public function setSite(string $site): self
    {
        $this->site = $site;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDebut(): ?\DateTimeInterface
    {
        return $this->debut;
    }

    public function setDebut(\DateTimeInterface $debut): self
    {
        $this->debut = $debut;

        return $this;
    }

    public function getFin(): ?\DateTimeInterface
    {
        return $this->fin;
    }

    public function setFin(\DateTimeInterface $fin): self
    {
        $this->fin = $fin;

        return $this;
    }
}
