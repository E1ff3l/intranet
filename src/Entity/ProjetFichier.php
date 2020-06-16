<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProjetFichierRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable
 */
class ProjetFichier
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fichier;

    /**
     * @Vich\UploadableField(
     *      mapping="projet_fichiers", 
     *      fileNameProperty="fichier"
     * )
     * @var File
     */
    private $fichierFile;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Projet", inversedBy="projetFichiers")
     */
    private $projet;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateModification;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFichier(): ?string
    {
        return $this->fichier;
    }

    public function setFichier(string $fichier): self
    {
        $this->fichier = $fichier;

        return $this;
    }

    public function setFichierFile(File $fichier = null)
    {
        $this->fichierFile = $fichier;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ( $fichier ) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getFichierFile()
    {
        return $this->fichierFile;
    }

    public function getProjet(): ?Projet
    {
        return $this->projet;
    }

    public function setProjet(?Projet $projet): self
    {
        $this->projet = $projet;

        return $this;
    }

    public function getDateModification(): ?\DateTimeInterface
    {
        $this->dateModification = new \DateTime();
        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function setDateModification()
    {
        $this->dateModification = new \DateTime();
        return $this;
    }

}
