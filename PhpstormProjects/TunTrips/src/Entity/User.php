<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Exception;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use PhpParser\Node\Scalar\String_;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @UniqueEntity(fields="email", message="This email is already taken.")
 * @ORM\HasLifecycleCallbacks()
 */
class User implements UserInterface, \Serializable
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     * @Assert\Length(min="3",minMessage = "Your first name must be at least 3 characters long")
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=false)
     * @Assert\Length(min ="3",minMessage = "Your first last must be at least 3 characters long")
     */
    private $prenom;

    /**
     * @var string

     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="passwd", type="string", length=255, nullable=false)
     * @Assert\Length(min ="8",minMessage = "Your passwd  must be at least 8 characters long")
     * @Assert\EqualTo(propertyPath="confirm_password",message="vous n\'avez pas tapez le meme mot de passe ")
     */
    private $passwd;
    /**
     * @Assert\EqualTo(propertyPath="passwd",message="vous n'avez pas tapez le meme mot de passe ")
     */
    public $confirm_password ;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255, nullable=false)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=255, nullable=false)
     */
    private $role;

    /**
     * @var string|null
     *
     * @ORM\Column(name="photo", type="string", length=1000, nullable=true, options={"default"="NULL"})
     * @Assert\File(mimeTypes={"image/png","image/jpg"})
     */
    private $photo ;

    /**
     * @var string
     *
     * @ORM\Column(name="num_tel", type="string", length=255, nullable=false)
     */
    private $numTel;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="valide", type="boolean", nullable=true, options={"default"="NULL"})
     */
    private $valide = 'NULL';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="etat", type="boolean", nullable=true, options={"default"="NULL"})
     */
    private $etat = 'NULL';

    public function getId(): ?int
    {
        return $this->id;
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

    public function __toString():String
    {
        return $this->id;
        return $this->email;
        return $this->nom;
        return $this->passwd;
        return $this->prenom;


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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPasswd(): ?string
    {
        return $this->passwd;
    }

    public function setPasswd(string $passwd): self
    {
        $this->passwd = $passwd;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getPhoto():?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo):self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getNumTel(): ?string
    {
        return $this->numTel;
    }

    public function setNumTel(string $numTel): self
    {
        $this->numTel = $numTel;

        return $this;
    }

    public function getValide(): ?bool
    {
        return $this->valide;
    }

    public function setValide(?bool $valide): self
    {
        $this->valide = $valide;

        return $this;
    }

    public function getEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(?bool $etat): self
    {
        $this->etat = $etat;

        return $this;
    }


    public function getRoles()
    {
return ['ROLE_USER'] ;
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUsername()
    {
        // TODO: Implement getUsername() method.
    }

    public function getPassword()
    {
        // TODO: Implement getPassword() method.
    }

    public function serialize()
    {
        // TODO: Implement serialize() method.
    }

    public function unserialize($data)
    {
        // TODO: Implement unserialize() method.
    }
}
