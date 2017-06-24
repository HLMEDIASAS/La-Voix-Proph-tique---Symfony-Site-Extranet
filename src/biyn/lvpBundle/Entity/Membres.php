<?php

# : DOCS
# : https://symfony.com/doc/current/security/entity_provider.html

namespace biyn\lvpBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity; 
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * Membres
 *
 * @ORM\Table(name="membres")
 * @UniqueEntity(fields="email", message="Un membre existe déjà avec cet email.")
 * @ORM\Entity(repositoryClass="biyn\lvpBundle\Repository\MembresRepository")
 */
class Membres implements AdvancedUserInterface, \Serializable
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
     * @ORM\Column(name="prenom", type="string", length=50)
     * @Assert\Length(max=50, maxMessage="Le prenom doit faire au maximum {{ limit }} caractères.")
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=50)
     * @Assert\Length(max=50, maxMessage="Le nom doit faire au maximum {{ limit }} caractères.")
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=50, unique=true)
     * @Assert\Length(max=50, maxMessage="L'email doit faire au maximum {{ limit }} caractères.")
     * @Assert\Email(
     *     message = "L'adresse '{{ value }}' n'est pas valide.",
     *     checkMX = true
     * )
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="mdp", type="string", length=255)
     */
    private $mdp;
    
    private $plaintextpassword;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateinscription", type="datetime")
     * @Assert\DateTime()
     */
    private $dateinscription;

    /**
     * @var bool
     *
     * @ORM\Column(name="isactive", type="boolean")
     */
    private $isactive;
    
    /**
     * @ORM\Column(name="salt", type="string", length=255)
     */
    private $salt;
    
    /**
     * @ORM\Column(name="roles", type="array")
     */
    private $roles;

    public function __construct() {
        
        # Quelques valeurs par défaut
        $this->isactive = true;
        $this->dateinscription = new \DateTime();
        $this->plaintextpassword = bin2hex(random_bytes(4));
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
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Membres
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Membres
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Membres
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set mdp
     *
     * @param string $mdp
     *
     * @return Membres
     */
    public function setMdp($mdp)
    {
        $this->mdp = $mdp;

        return $this;
    }

    /**
     * Get mdp
     *
     * @return string
     */
    public function getMdp()
    {
        return $this->mdp;
    }

    /**
     * Set dateinscription
     *
     * @param \DateTime $dateinscription
     *
     * @return Membres
     */
    public function setDateinscription($dateinscription)
    {
        $this->dateinscription = $dateinscription;

        return $this;
    }

    /**
     * Get dateinscription
     *
     * @return \DateTime
     */
    public function getDateinscription()
    {
        return $this->dateinscription;
    }

    /**
     * Set isactive
     *
     * @param boolean $isactive
     *
     * @return Membres
     */
    public function setIsactive($isactive)
    {
        $this->isactive = $isactive;

        return $this;
    }

    /**
     * Get isactive
     *
     * @return bool
     */
    public function getIsactive()
    {
        return $this->isactive;
    }

    /**
     * {@inheritDoc}
     * @see \Symfony\Component\Security\Core\User\UserInterface::getRoles()
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * {@inheritDoc}
     * @see \Symfony\Component\Security\Core\User\UserInterface::getPassword()
     */
    public function getPassword()
    {
        return $this->mdp;
    }

    /**
     * {@inheritDoc}
     * @see \Symfony\Component\Security\Core\User\UserInterface::getSalt()
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * {@inheritDoc}
     * @see \Symfony\Component\Security\Core\User\UserInterface::getUsername()
     */
    public function getUsername()
    {
        return $this->email;
    }
    
    /**
     * @param field_type $plaintextpassword
     */
    public function setPlaintextpassword($plaintextpassword)
    {
        $this->plaintextpassword = $plaintextpassword;
    }

    /**
     * @return the $plaintextpassword
     */
    public function getPlaintextpassword()
    {
        return $this->plaintextpassword;
    }

    /**
     * {@inheritDoc}
     * @see \Symfony\Component\Security\Core\User\UserInterface::eraseCredentials()
     */
    public function eraseCredentials()
    {
        
    }
    
    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->mdp,
            // see section on salt below
            // $this->salt,
        ));
    }
    
    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->mdp,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }


    /**
     * Set salt
     *
     * @param string $salt
     *
     * @return Membres
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Set roles
     *
     * @param array $roles
     *
     * @return Membres
     */
    public function setRoles($roles)
    {
        $this->roles[] = $roles;

        return $this;
    }
    /**
     * {@inheritDoc}
     * @see \Symfony\Component\Security\Core\User\AdvancedUserInterface::isAccountNonExpired()
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * {@inheritDoc}
     * @see \Symfony\Component\Security\Core\User\AdvancedUserInterface::isAccountNonLocked()
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * {@inheritDoc}
     * @see \Symfony\Component\Security\Core\User\AdvancedUserInterface::isCredentialsNonExpired()
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * {@inheritDoc}
     * @see \Symfony\Component\Security\Core\User\AdvancedUserInterface::isEnabled()
     */
    public function isEnabled()
    {
        return $this->isactive;
    }
    
    

}
