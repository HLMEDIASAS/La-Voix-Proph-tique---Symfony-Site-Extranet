<?php

namespace biyn\lvpBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Cours
 *
 * @ORM\Table(name="cours")
 * @ORM\Entity(repositoryClass="biyn\lvpBundle\Repository\CoursRepository")
 */
class Cours
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=50)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(name="mp3", type="string", length=50, nullable=true)
     *
     * @Assert\NotBlank(message="Vous devez soumettre un fichier mp3.")
     * @Assert\File(mimeTypes={ "audio/mp3", "audio/mpeg" })
     */
    private $mp3;

    /**
     * @var string
     *
     * @ORM\Column(name="pdf", type="string", length=50, nullable=true)
     */
    private $pdf;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateajout", type="datetimetz")
     */
    private $dateajout;
    
    
    public function __construct() {
    
        # Quelques valeurs par défaut
        $this->dateajout = new \DateTime();
        $this->pdf = null;
        $this->description = null;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set titre
     *
     * @param string $titre
     *
     * @return Cours
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Cours
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set mp3
     *
     * @param string $mp3
     *
     * @return Cours
     */
    public function setMp3($mp3)
    {
        $this->mp3 = $mp3;

        return $this;
    }

    /**
     * Get mp3
     *
     * @return string
     */
    public function getMp3()
    {
        return $this->mp3;
    }

    /**
     * Set pdf
     *
     * @param string $pdf
     *
     * @return Cours
     */
    public function setPdf($pdf)
    {
        $this->pdf = $pdf;

        return $this;
    }

    /**
     * Get pdf
     *
     * @return string
     */
    public function getPdf()
    {
        return $this->pdf;
    }

    /**
     * Set dateajout
     *
     * @param \DateTime $dateajout
     *
     * @return Cours
     */
    public function setDateajout($dateajout)
    {
        $this->dateajout = $dateajout;

        return $this;
    }

    /**
     * Get dateajout
     *
     * @return \DateTime
     */
    public function getDateajout()
    {
        return $this->dateajout;
    }
}

