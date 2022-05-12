<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\HebergementRepository;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=HebergementRepository::class)
 */
class Hebergement
{
    /**
     * @var int
     *
     * @ORM\Column(name="idheberg", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idheberg;

    public function __toString():?string
    {
return $this->idheberg;
    }

    /**
     * @var string
     * @Assert\NotBlank(message="address is required")
     * @ORM\Column(name="address", type="string", length=255, nullable=false)
     */
    private $address;

    /**
     * @var string
     * @Assert\NotBlank(message="Please upload image")
     * @ORM\Column(name="photo", type="string", length=1000, nullable=true)
     */
    private $photo;


    /**
     * @Assert\NotBlank(message="type is required")
     * @ORM\Column(type="string", length=255)
     */
    private $type;


    /**
     * @Assert\NotBlank(message="capacity is required")
     * @ORM\Column(type="integer")
     */
    private $capacitechambre;

    /**
     * @Assert\NotBlank(message="Etat is required")
     * @ORM\Column(type="smallint")
     */
    private $disponibilite;

    /**
     * @Assert\NotBlank(message="parking is required")
     * @ORM\Column(type="smallint")
     */
    private $disponibilite_parking;

    /**
     * @ORM\Column(type="float")
     */
    private $tarif;



    public function getIdheberg(): ?int
    {
        return $this->idheberg;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }


    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }


    public function getCapacitechambre(): ?int
    {
        return $this->capacitechambre;
    }

    public function setCapacitechambre(int $capacitechambre): self
    {
        $this->capacitechambre = $capacitechambre;

        return $this;
    }

    public function getDisponibilite(): ?int
    {
        return $this->disponibilite;
    }

    public function setDisponibilite(int $disponibilite): self
    {
        $this->disponibilite = $disponibilite;

        return $this;
    }

    public function getDisponibiliteParking(): ?int
    {
        return $this->disponibilite_parking;
    }

    public function setDisponibiliteParking(int $disponibilite_parking): self
    {
        $this->disponibilite_parking = $disponibilite_parking;

        return $this;
    }

    public function getTarif(): ?float
    {
        return $this->tarif;
    }

    public function setTarif(float $tarif): self
    {
        $this->tarif = $tarif;

        return $this;
    }




}
