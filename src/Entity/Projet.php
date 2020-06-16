<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProjetRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Projet
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank( message = "Ce champ est obligatoire!" )
     */
    private $titre;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank( message = "Ce champ est obligatoire!" )
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank( message = "Ce champ est obligatoire!" )
     * @Assert\Regex(
     *     pattern="/^\d+/",
     *     message="La valeur n'est pas correcte!"
     * )
     */
    private $prix;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateSaisie;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateFin;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateFacturation;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datePaiement;

    private $generationPdf;

    private $fichierFacture;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProjetFichier", mappedBy="projet", orphanRemoval=true)
     */
    private $projetFichiers;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="projets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ProjetEtat", inversedBy="projets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $projetEtat;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $descriptionInterne;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $indice;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProjetAcompte", mappedBy="projet", orphanRemoval=true)
     * @Assert\Valid()
     */
    private $projetAcomptes;

    public function __construct()
    {
        $this->projetFichiers = new ArrayCollection();
        $this->projetAcomptes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDateSaisie(): ?\DateTimeInterface
    {
        return $this->dateSaisie;
    }

    /**
     * @ORM\PrePersist
     */
    public function setDateSaisie()
    {
        $this->dateSaisie = new \DateTime();
        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getDateFacturation(): ?\DateTimeInterface
    {
        return $this->dateFacturation;
    }

    public function setDateFacturation(?\DateTimeInterface $dateFacturation): self
    {
        $this->dateFacturation = $dateFacturation;

        return $this;
    }

    public function getDatePaiement(): ?\DateTimeInterface
    {
        return $this->datePaiement;
    }

    public function setDatePaiement(?\DateTimeInterface $datePaiement): self
    {
        $this->datePaiement = $datePaiement;

        return $this;
    }

    public function getGenerationPdf() {
        return $this->generationPdf;
    }

    public function setGenerationPdf( $generationPdf ) {
        $this->generationPdf = $generationPdf;

        return $this;
    }

    public function getFichierFacture() {
        return $this->fichierFacture;
    }

    public function setFichierFacture( $fichierFacture ) {
        $this->fichierFacture = $fichierFacture;

        return $this;
    }

    /**
     * @return Collection|ProjetFichier[]
     */
    public function getProjetFichiers(): Collection
    {
        return $this->projetFichiers;
    }

    public function addProjetFichier(ProjetFichier $projetFichier): self
    {
        if (!$this->projetFichiers->contains($projetFichier)) {
            $this->projetFichiers[] = $projetFichier;
            $projetFichier->setProjet($this);
        }

        return $this;
    }

    public function removeProjetFichier(ProjetFichier $projetFichier): self
    {
        if ($this->projetFichiers->contains($projetFichier)) {
            $this->projetFichiers->removeElement($projetFichier);
            
            // Set the owning side to null (unless already changed)
            if ($projetFichier->getProjet() === $this) {
                $projetFichier->setProjet(null);
            }
        }

        return $this;
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

    public function getProjetEtat(): ?ProjetEtat
    {
        return $this->projetEtat;
    }

    public function setProjetEtat(?ProjetEtat $projetEtat): self
    {
        $this->projetEtat = $projetEtat;

        return $this;
    }

    public function getDescriptionInterne(): ?string
    {
        return $this->descriptionInterne;
    }

    public function setDescriptionInterne(?string $descriptionInterne): self
    {
        $this->descriptionInterne = $descriptionInterne;

        return $this;
    }

    public function getIndice(): ?int
    {
        return $this->indice;
    }

    public function setIndice(?int $indice): self
    {
        $this->indice = $indice;

        return $this;
    }

    /**
     * @return Collection|ProjetAcompte[]
     */
    public function getProjetAcomptes(): Collection
    {
        return $this->projetAcomptes;
    }

    public function addProjetAcompte(ProjetAcompte $projetAcompte): self
    {
        if (!$this->projetAcomptes->contains($projetAcompte)) {
            $this->projetAcomptes[] = $projetAcompte;
            $projetAcompte->setProjet($this);
        }

        return $this;
    }

    public function removeProjetAcompte(ProjetAcompte $projetAcompte): self
    {
        if ($this->projetAcomptes->contains($projetAcompte)) {
            $this->projetAcomptes->removeElement($projetAcompte);
            // set the owning side to null (unless already changed)
            if ($projetAcompte->getProjet() === $this) {
                $projetAcompte->setProjet(null);
            }
        }

        return $this;
    }

}
