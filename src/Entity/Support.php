<?php

namespace App\Entity;

use App\Repository\SupportRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SupportRepository::class)]
class Support
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message:"Support obligatoire")]
    private ?string $support = null;

    #[ORM\ManyToMany(targetEntity: Album::class, mappedBy: 'support')]
    private Collection $albums;

    #[ORM\OneToMany(mappedBy: 'albumSupport', targetEntity: Album::class)]
    private Collection $albumsSupport;

    public function __construct()
    {
        $this->albums = new ArrayCollection();
        $this->albumsSupport = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSupport(): ?string
    {
        return $this->support;
    }

    public function setSupport(string $support): static
    {
        $this->support = $support;

        return $this;
    }

    /**
     * @return Collection<int, Album>
     */
    public function getAlbums(): Collection
    {
        return $this->albums;
    }

    public function addAlbum(Album $album): static
    {
        if (!$this->albums->contains($album)) {
            $this->albums->add($album);
            $album->addSupport($this);
        }

        return $this;
    }

    public function removeAlbum(Album $album): static
    {
        if ($this->albums->removeElement($album)) {
            $album->removeSupport($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Album>
     */
    public function getAlbumsSupport(): Collection
    {
        return $this->albumsSupport;
    }

    public function addAlbumsSupport(Album $albumsSupport): static
    {
        if (!$this->albumsSupport->contains($albumsSupport)) {
            $this->albumsSupport->add($albumsSupport);
            $albumsSupport->setAlbumSupport($this);
        }

        return $this;
    }

    public function removeAlbumsSupport(Album $albumsSupport): static
    {
        if ($this->albumsSupport->removeElement($albumsSupport)) {
            // set the owning side to null (unless already changed)
            if ($albumsSupport->getAlbumSupport() === $this) {
                $albumsSupport->setAlbumSupport(null);
            }
        }

        return $this;
    }
}
