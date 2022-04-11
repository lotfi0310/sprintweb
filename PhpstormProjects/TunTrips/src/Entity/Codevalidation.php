<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Codevalidation
 *
 * @ORM\Table(name="codevalidation", indexes={@ORM\Index(name="email", columns={"email"}), @ORM\Index(name="email_2", columns={"email"})})
 * @ORM\Entity
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

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="email", referencedColumnName="email")
     * })
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

    public function getEmail(): ?User
    {
        return $this->email;
    }

    public function setEmail(?User $email): self
    {
        $this->email = $email;

        return $this;
    }


}
