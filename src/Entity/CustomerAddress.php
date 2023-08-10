<?php

namespace App\Entity;

use App\Repository\CustomerAddressRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CustomerAddressRepository::class)]
class CustomerAddress
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Address 1 is required!')]
    #[Assert\Length(max: 255, maxMessage: 'Your first address cannot be longer than {{ limit }} characters')]
    private ?string $address1 = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(max: 255, maxMessage: 'Your second address cannot be longer than {{ limit }} characters')]
    private ?string $address2 = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'City is required!')]
    #[Assert\Length(max: 255, maxMessage: 'City name cannot be longer than {{ limit }} characters')]
    private ?string $city = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $state = null;

    #[ORM\Column(length: 45)]
    #[Assert\NotBlank(message: 'Zipcode is required!')]
    #[Assert\Length(max: 45, maxMessage: 'Your zipcode cannot be longer than {{ limit }} characters')]
    private ?string $zipcode = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: 'You must select a country!')]
    private ?Country $country = null;

    public function toArray()
    {
        return [
            'id' => $this->id,
            'address1' => $this->address1,
            'address2' => $this->address2,
            'city' => $this->city,
            'state' => $this->state,
            'zipcode' => $this->zipcode,
            'country' => $this->country->toArray()
        ];
    }

    public function __toString(): string
    {
        return $this->address1 . ", " . $this->city . " " . $this->zipcode . ", " . $this->country->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress1(): ?string
    {
        return $this->address1;
    }

    public function setAddress1(string $address1): static
    {
        $this->address1 = $address1;

        return $this;
    }

    public function getAddress2(): ?string
    {
        return $this->address2;
    }

    public function setAddress2(string $address2): static
    {
        $this->address2 = $address2;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): static
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): static
    {
        $this->country = $country;

        return $this;
    }
}
