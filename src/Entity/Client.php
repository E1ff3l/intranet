<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 */
class Client
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank( message = "Ce champ est obligatoire!" )
     */
    private $societe;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Url( message = "L'URL '{{ value }}' est incorrecte!" )
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank( message = "Ce champ est obligatoire!" )
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank( message = "Ce champ est obligatoire!" )
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank( message = "Ce champ est obligatoire!" )
     * @Assert\Email(
     *      message = "L'E-mail '{{ value }}' est incorrect!"
     * )
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $tel;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $mobile;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $fax;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank( message = "Ce champ est obligatoire!" )
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresse_suite;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\NotBlank( message = "Ce champ est obligatoire!" )
     */
    private $cp;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank( message = "Ce champ est obligatoire!" )
     */
    private $ville;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pays", inversedBy="clients")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pays;

    /**
     * @ORM\Column(type="boolean")
     */
    private $online;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProjetHebergement", mappedBy="client")
     */
    private $projetHebergements;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Projet", mappedBy="client")
     */
    private $projetEtat;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Projet", mappedBy="client")
     */
    private $projets;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Hosting", mappedBy="client")
     */
    private $hostings;

    public function __construct()
    {
        $this->projetHebergements = new ArrayCollection();
        $this->projetEtat = new ArrayCollection();
        $this->projets = new ArrayCollection();
        $this->hostings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSociete(): ?string
    {
        return $this->societe;
    }

    public function setSociete(string $societe): self
    {
        $this->societe = $societe;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(?string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(?string $mobile): self
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function getFax(): ?string
    {
        return $this->fax;
    }

    public function setFax(?string $fax): self
    {
        $this->fax = $fax;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getAdresseSuite(): ?string
    {
        return $this->adresse_suite;
    }

    public function setAdresseSuite(?string $adresse_suite): self
    {
        $this->adresse_suite = $adresse_suite;

        return $this;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(string $cp): self
    {
        $this->cp = $cp;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getPays(): ?Pays
    {
        return $this->pays;
    }

    public function setPays(?Pays $pays): self
    {
        $this->pays = $pays;

        return $this;
    }

    public function getOnline(): ?bool
    {
        return $this->online;
    }

    public function setOnline(bool $online): self
    {
        $this->online = $online;

        return $this;
    }

    /**
     * @return Collection|ProjetHebergement[]
     */
    public function getProjetHebergements(): Collection
    {
        return $this->projetHebergements;
    }

    public function addProjetHebergement(ProjetHebergement $projetHebergement): self
    {
        if (!$this->projetHebergements->contains($projetHebergement)) {
            $this->projetHebergements[] = $projetHebergement;
            $projetHebergement->setClient($this);
        }

        return $this;
    }

    public function removeProjetHebergement(ProjetHebergement $projetHebergement): self
    {
        if ($this->projetHebergements->contains($projetHebergement)) {
            $this->projetHebergements->removeElement($projetHebergement);
            // set the owning side to null (unless already changed)
            if ($projetHebergement->getClient() === $this) {
                $projetHebergement->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Projet[]
     */
    public function getProjetEtat(): Collection
    {
        return $this->projetEtat;
    }

    public function addProjetEtat(Projet $projetEtat): self
    {
        if (!$this->projetEtat->contains($projetEtat)) {
            $this->projetEtat[] = $projetEtat;
            $projetEtat->setClient($this);
        }

        return $this;
    }

    public function removeProjetEtat(Projet $projetEtat): self
    {
        if ($this->projetEtat->contains($projetEtat)) {
            $this->projetEtat->removeElement($projetEtat);
            // set the owning side to null (unless already changed)
            if ($projetEtat->getClient() === $this) {
                $projetEtat->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Projet[]
     */
    public function getProjets(): Collection
    {
        return $this->projets;
    }

    public function addProjet(Projet $projet): self
    {
        if (!$this->projets->contains($projet)) {
            $this->projets[] = $projet;
            $projet->setClient($this);
        }

        return $this;
    }

    public function removeProjet(Projet $projet): self
    {
        if ($this->projets->contains($projet)) {
            $this->projets->removeElement($projet);
            // set the owning side to null (unless already changed)
            if ($projet->getClient() === $this) {
                $projet->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Hosting[]
     */
    public function getHostings(): Collection
    {
        return $this->hostings;
    }

    public function addHosting(Hosting $hosting): self
    {
        if (!$this->hostings->contains($hosting)) {
            $this->hostings[] = $hosting;
            $hosting->setClient($this);
        }

        return $this;
    }

    public function removeHosting(Hosting $hosting): self
    {
        if ($this->hostings->contains($hosting)) {
            $this->hostings->removeElement($hosting);
            // set the owning side to null (unless already changed)
            if ($hosting->getClient() === $this) {
                $hosting->setClient(null);
            }
        }

        return $this;
    }

}
