<?php

namespace App\Entity;
use DateTimeInterface;
use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $idUser;

    /**
     * @Assert\Date()
     * @Assert\GreaterThan("today")
     * Assert\NotBlank(message="Date is required")
     * @ORM\Column(type="date")
     */
    private $dateEntre;

    /**
     * @Assert\Date()
     * @Assert\Expression(
     *     "this.getDateEntre() < this.getDateSortie()",
     *     message="La date fin ne doit pas être antérieure à la date début"
     * )
     * Assert\NotBlank(message="Date is required")
     * @ORM\Column(type="date")
     */
    private $dateSortie;

    /**
     * Assert\NotBlank(message="Prix is required")
     * @ORM\Column(type="float")
     */
    private $prix;

    /**
     * @ORM\Column(type="smallint")
     */
    private $confirmer;

    /**
     * @ORM\OneToOne(targetEntity=Hebergement::class, mappedBy="idheberg", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true, referencedColumnName="idheberg")
     */
    private $idheberge;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getDateEntre(): ?\DateTimeInterface
    {
        return $this->dateEntre;
    }

    public function setDateEntre(\DateTimeInterface $dateEntre): self
    {
        $this->dateEntre = $dateEntre;

        return $this;
    }

    public function getDateSortie(): ?\DateTimeInterface
    {
        return $this->dateSortie;
    }

    public function setDateSortie(\DateTimeInterface $dateSortie): self
    {
        $this->dateSortie = $dateSortie;

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

    public function getConfirmer(): ?int
    {
        return $this->confirmer;
    }

    public function setConfirmer(int $confirmer): self
    {
        $this->confirmer = $confirmer;

        return $this;
    }

    public function getIdheberge(): ?Hebergement
    {
        return $this->idheberge;
    }

    public function setIdheberge(Hebergement $idheberge): self
    {
        $this->idheberge = $idheberge;

        return $this;
    }



}
