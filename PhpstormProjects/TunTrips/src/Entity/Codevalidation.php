<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Codevalidation
 *
 * @ORM\Table(name="codevalidation", indexes={@ORM\Index(name="email", columns={"email"})})
 * @ORM\Entity(repositoryClass="App\Repository\CodevalidationRepository")
 */
class Codevalidation
{
    /**
     * @var int
     *
     * @ORM\Column(name="idcode", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcode;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=10, nullable=false)
     */
    private $code;

    public function __toString():?string
    {
        return $this->idcode;
        return $this->email;
        return $this->code;
        return $this->coderecMp;
    }


    /**
     * @var string|null
     *
     * @ORM\Column(name="coderec_mp", type="string", length=15, nullable=true)
     */
    private $coderecMp;

    /**
     * @ORM\Column(name="email", type="string", length=15, nullable=false)
     */
    private $email;

    public function getIdcode(): ?int
    {
        return $this->idcode;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getCoderecMp(): ?string
    {
        return $this->coderecMp;
    }

    public function setCoderecMp(?string $coderecMp): self
    {
        $this->coderecMp = $coderecMp;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }


}
