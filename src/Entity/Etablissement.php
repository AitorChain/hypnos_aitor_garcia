<?php

namespace App\Entity;

use App\Repository\EtablissementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: EtablissementRepository::class)]
class Etablissement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 50)]
    private $nom;

    #[ORM\Column(type: 'string', length: 50)]
    private $ville;

    #[ORM\Column(type: 'string', length: 50)]
    private $addrese;

    #[ORM\Column(type: 'text')]
    private $description;

    #[ORM\OneToMany(mappedBy: 'etablissement', targetEntity: Suite::class, orphanRemoval: true)]
    private $suites;

    #[ORM\JoinColumn(onDelete: "SET NULL")]
    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'etablissements')]
    private $gerant;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $photoFilename;

    #[ORM\Column(type: 'string', length: 255)]
    #[Gedmo\Slug(fields: ['nom', 'ville'])]
    private $slug;


    public function __construct()
    {
        $this->suites = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nom;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getAddrese(): ?string
    {
        return $this->addrese;
    }

    public function setAddrese(string $addrese): self
    {
        $this->addrese = $addrese;

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

    /**
     * @return Collection<int, Suite>
     */
    public function getSuites(): Collection
    {
        return $this->suites;
    }

    public function addSuite(Suite $suite): self
    {
        if (!$this->suites->contains($suite)) {
            $this->suites[] = $suite;
            $suite->setEtablissement($this);
        }

        return $this;
    }

    public function removeSuite(Suite $suite): self
    {
        if ($this->suites->removeElement($suite)) {
            // set the owning side to null (unless already changed)
            if ($suite->getEtablissement() === $this) {
                $suite->setEtablissement(null);
            }
        }

        return $this;
    }

    public function getGerant(): ?Utilisateur
    {
        return $this->gerant;
    }

    public function setGerant(?Utilisateur $gerant): self
    {
        $this->gerant = $gerant;

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

}
