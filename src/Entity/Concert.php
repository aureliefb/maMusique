<?php

namespace App\Entity;

use App\Repository\ConcertRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ConcertRepository::class)]
class Concert
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $date_concert = null;

    #[ORM\ManyToOne(inversedBy: 'concerts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message:"Artiste obligatoire")]
    private ?Artiste $artiste = null;

    #[ORM\ManyToOne(inversedBy: 'concerts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message:"Lieu obligatoire")]
    private ?Lieu $lieu = null;

    #[ORM\Column]
    private ?bool $is_festival = null;

    #[ORM\ManyToOne(inversedBy: 'concerts')]
    private ?Festival $Festival = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getDateConcert(): ?string
    {
        return $this->date_concert;
    }

    public function setDateConcert(?string $date_concert): static
    {
        $this->date_concert = $date_concert;

        return $this;
    }

    public function getArtiste(): ?Artiste
    {
        return $this->artiste;
    }

    public function setArtiste(?Artiste $artiste): static
    {
        $this->artiste = $artiste;

        return $this;
    }

    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    public function setLieu(?Lieu $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function isFestival(): ?bool
    {
        return $this->is_festival;
    }

    public function setIsFestival(bool $is_festival): static
    {
        $this->is_festival = $is_festival;

        return $this;
    }

    public function setFestival(?Festival $festival): static
    {
        $this->Festival = $festival;

        return $this;
    }
    public function getFestival(): ?Festival
    {
        return $this->Festival;
    }
}
