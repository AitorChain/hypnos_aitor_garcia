<?php

namespace App\Entity;

use App\Repository\SuiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SuiteRepository::class)]
class Suite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 50)]
    private $titre;

    #[ORM\Column(type: 'text')]
    private $description;

    #[ORM\Column(type: 'integer')]
    private $prix;

    #[ORM\Column(type: 'string', length: 255)]
    private $lien_booking;

    #[ORM\ManyToOne(targetEntity: Etablissement::class, inversedBy: 'suites')]
    #[ORM\JoinColumn(nullable: false)]
    private $etablissement;

    #[ORM\OneToMany(mappedBy: 'suite', targetEntity: Reservation::class, orphanRemoval: true)]
    private $reservations;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $photoFilename;

    #[ORM\OneToMany(mappedBy: 'suite', targetEntity: GallerieImage::class, cascade: ['persist'], orphanRemoval: true)]
    private $gallerieImages;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->gallerieImages = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->titre;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getLienBooking(): ?string
    {
        return $this->lien_booking;
    }

    public function setLienBooking(string $lien_booking): self
    {
        $this->lien_booking = $lien_booking;

        return $this;
    }

    public function getEtablissement(): ?Etablissement
    {
        return $this->etablissement;
    }

    public function setEtablissement(?Etablissement $etablissement): self
    {
        $this->etablissement = $etablissement;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setSuite($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getSuite() === $this) {
                $reservation->setSuite(null);
            }
        }

        return $this;
    }

    public function getPhotoFilename(): ?string
    {
        return $this->photoFilename;
    }

    public function setPhotoFilename(?string $photoFilename): self
    {
        $this->photoFilename = $photoFilename;

        return $this;
    }

    /**
     * @return Collection<int, GallerieImage>
     */
    public function getGallerieImages(): Collection
    {
        return $this->gallerieImages;
    }

    public function addGallerieImage(GallerieImage $gallerieImage): self
    {
        if (!$this->gallerieImages->contains($gallerieImage)) {
            $this->gallerieImages[] = $gallerieImage;
            $gallerieImage->setSuite($this);
        }

        return $this;
    }

    public function removeGallerieImage(GallerieImage $gallerieImage): self
    {
        if ($this->gallerieImages->removeElement($gallerieImage)) {
            // set the owning side to null (unless already changed)
            if ($gallerieImage->getSuite() === $this) {
                $gallerieImage->setSuite(null);
            }
        }

        return $this;
    }
}
