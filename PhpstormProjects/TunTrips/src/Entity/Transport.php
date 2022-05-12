<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * transport
 *
 * @ORM\Table(name="transport")
 * @ORM\Entity
 */
class Transport
{
    /**
     * @var int
     *
     * @ORM\Column(name="idtransport", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idtransport;

    public function __toString():?string
    {
        return $this->idtransport;
    }


    /**
     * @var string
     * @Assert\NotBlank(message=" champ doit etre non vide**")

     * @ORM\Column(name="type", type="string", length=20, nullable=false)
     */
    private $type;

    /**
     * @var int

     * @ORM\Column(name="capacite", type="integer", nullable=false)
     */
    private $capacite;


    /**
     * @var int
     * @Assert\NotBlank(message=" champ doit etre non vide**")
     * @Assert\Length(
     *max=11,min =8 , maxMessage="Le numéro valide ne depasse pas 11 chiffres",
     *
     *      minMessage=" Le numéro valide doit être composé de 8 chiffres au minimum  "
     *     )
     * @ORM\Column(name="numChauffeur", type="integer", nullable=false)
     */
    private $numchauffeur;

    /**
     * @var string
     *
     *
     *
     * @ORM\Column(name="immatricule", type="string", length=20, nullable=false)
     */
    private $immatricule;

    /**
     * @var bool
     *     *@Assert\NotBlank(message=" champ doit etre non vide**")

     * @ORM\Column(name="dispo", type="boolean", nullable=false)
     */
    private $dispo;

    /**
     * @var string
     *     *@Assert\NotBlank(message=" champ doit etre non vide**")

     * @ORM\Column(name="lieuDispo", type="string", length=20, nullable=false)
     */
    private $lieudispo;

    /**
     * @var int
     *     *@Assert\NotBlank(message=" champ doit etre non vide**")

     * @ORM\Column(name="idUser", type="string", length=20, nullable=false)
     */
    private $iduser;



    public function getType(): ?string
    {
        return $this->type;
    }

    public function getIdtransport(): ?int
    {
        return $this->idtransport;
    }

    public function setIdtransport(string $id): self
    {
        $this->idtransport = $id ;

        return $this;
    }
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(int $capacite): self
    {
        $this->capacite = $capacite;

        return $this;
    }

    public function getNumchauffeur(): ?int
    {
        return $this->numchauffeur;
    }

    public function setNumchauffeur(int $numchauffeur): self
    {
        $this->numchauffeur = $numchauffeur;

        return $this;
    }

    public function getImmatricule(): ?string
    {
        return $this->immatricule;
    }

    public function setImmatricule(string $immatricule): self
    {
        $this->immatricule = $immatricule;

        return $this;
    }

    public function getDispo(): ?bool
    {
        return $this->dispo;
    }

    public function setDispo(bool $dispo): self
    {
        $this->dispo = $dispo;

        return $this;
    }

    public function getLieudispo(): ?string
    {
        return $this->lieudispo;
    }

    public function setLieudispo(string $lieudispo): self
    {
        $this->lieudispo = $lieudispo;

        return $this;
    }

    public function getIduser(): ?int
    {
        return $this->iduser;
    }

    public function setIduser(int $iduser): self
    {
        $this->iduser = $iduser;

        return $this;
    }

    protected $captchaCode;

    public function getCaptchaCode()
    {
        return $this->captchaCode;
    }

    public function setCaptchaCode($captchaCode)
    {
        $this->captchaCode = $captchaCode;
    }



}
