<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RideRepository")
 */
class Ride
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=512)
     * @Assert\NotBlank
     * @Assert\NotNull
     * @Assert\Type("string")
     * @Assert\Length(
     *      min = 3,
     *      max = 255,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     */
    private $client_name;

    /**
     * @ORM\Column(type="string", length=32)
     * @Assert\NotBlank
     * @Assert\NotNull
     * @Assert\Type("string")
     * @Assert\Regex("/^(((\+44\s?\d{4}|\(?0\d{4}\)?)\s?\d{3}\s?\d{3})|((\+44\s?\d{3}|\(?0\d{3}\)?)\s?\d{3}\s?\d{4})|((\+44\s?\d{2}|\(?0\d{2}\)?)\s?\d{4}\s?\d{4}))(\s?\#(\d{4}|\d{3}))?$/")
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=8)
     * @Assert\NotBlank
     * @Assert\NotNull
     * @Assert\Type("string")
     * @Assert\Regex("/\b([A-Z]{2}|[A-Z]\d|\d[A-Z])\s?\d{3,4}\b/")
     */
    private $flight_number;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank
     * @Assert\NotNull
     * @Assert\DateTime
     */
    private $arrived_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Airport", inversedBy="rides")
     * @ORM\JoinColumn(nullable=false)
     */
    private $airport;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $terminal;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClientName(): ?string
    {
        return $this->client_name;
    }

    public function setClientName(string $client_name): self
    {
        $this->client_name = $client_name;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone[0] !== '+' ? "+{$phone}" : $phone;

        return $this;
    }

    public function getFlightNumber(): ?string
    {
        return $this->flight_number;
    }

    public function setFlightNumber(string $flight_number): self
    {
        $this->flight_number = $flight_number;

        return $this;
    }

    public function getArrivedAt(): ?\DateTimeInterface
    {
        return $this->arrived_at;
    }

    public function setArrivedAt(\DateTimeInterface $arrived_at): self
    {
        $this->arrived_at = $arrived_at;

        return $this;
    }

    public function getAirport(): ?Airport
    {
        return $this->airport;
    }

    public function setAirport(?Airport $airport): self
    {
        $this->airport = $airport;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getTerminal(): ?string
    {
        return $this->terminal;
    }

    public function setTerminal(?string $terminal): self
    {
        $this->terminal = $terminal;

        return $this;
    }

    public function getDesciption(): string
    {
        return "
                Airport: {$this->getAirport()->getName()} \n
                Terminal: {$this->getTerminal()} \n
                Flight Number: {$this->getFlightNumber()} \n
                Arrived: {$this->getArrivedAt()->format('d M Y H:i')} \n
                Client: {$this->getClientName()} {$this->getPhone()} \n
        ";
    }
}
