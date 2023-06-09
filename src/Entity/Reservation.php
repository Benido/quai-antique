<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $seats_number = 1;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $comment = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Restaurant $restaurant = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?Client $client = null;

    #[ORM\ManyToMany(targetEntity: Allergen::class, inversedBy: 'reservations')]
    private Collection $allergens;

    public function __construct()
    {
        $this->date = new DateTime();
        $this->allergens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    public function setDate(?DateTime $date): self
    {
        if (null !== $date)
        $this->date = $date;

        return $this;
    }

    public function getDay(): ?DateTime
    {
        return new DateTime($this->date?->format('Y-m-d')) ;
    }

    public function setDay(DateTime $date): self
    {
        $this->date->setDate($date->format('Y'), $date->format('m'), $date->format('d')) ;
        return $this;
    }

    public function getTime(): ?DateTime
    {
        return new DateTime($this->date?->format('H:i'));
    }

    public function setTime(\DateTimeInterface $time): self
    {
        $this->date->setTime($time->format('H'), $time->format('i')) ;

        return $this;
    }

    public function getLocaleDate(): ?String
    {
        $date = $this->date;
        $dateFormatter = new \IntlDateFormatter(
            'fr-FR',
            \IntlDateFormatter::FULL,
            \IntlDateFormatter::FULL,
            null,
            null,
            'eeee dd MMM'
        );
        return $dateFormatter->format($date);
    }

    public function getSeatsNumber(): ?int
    {
        return $this->seats_number;
    }

    public function setSeatsNumber(int $seats_number): self
    {
        $this->seats_number = $seats_number;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getRestaurant(): ?Restaurant
    {
        return $this->restaurant;
    }

    public function setRestaurant(?Restaurant $restaurant): self
    {
        $this->restaurant = $restaurant;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

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

    public function getAllergens(): ArrayCollection|Collection
    {
        return $this->allergens;
    }

    public function addAllergen(Allergen $allergen): self
    {
        if (!$this->allergens->contains($allergen)) {
            $this->allergens->add($allergen);
        }

        return $this;
    }

    public function removeAllergen(Allergen $allergen): self
    {
        $this->allergens->removeElement($allergen);

        return $this;
    }
}
