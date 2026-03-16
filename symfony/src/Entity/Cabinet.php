<?php

namespace App\Entity;

use App\Repository\CabinetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CabinetRepository::class)]
class Cabinet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    private ?string $telephone = null;

    /**
     * @var Collection<int, Orthophoniste>
     */
    #[ORM\OneToMany(targetEntity: Orthophoniste::class, mappedBy: 'cabinet', orphanRemoval: true, cascade: ["persist", "remove"])]
    private Collection $orthophonistes;

    /**
     * @var Collection<int, Image>
     */
    #[ORM\OneToMany(targetEntity: Image::class, mappedBy: 'cabinet', orphanRemoval: true, cascade: ['persist'])]
    private Collection $imagesCabinet;

    public function __construct()
    {
        $this->orthophonistes = new ArrayCollection();
        $this->imagesCabinet = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * @return Collection<int, Orthophoniste>
     */
    public function getOrthophonistes(): Collection
    {
        return $this->orthophonistes;
    }

    public function addOrthophoniste(Orthophoniste $orthophoniste): static
    {
        if (!$this->orthophonistes->contains($orthophoniste)) {
            $this->orthophonistes->add($orthophoniste);
            $orthophoniste->setCabinet($this);
        }

        return $this;
    }

    public function removeOrthophoniste(Orthophoniste $orthophoniste): static
    {
        if ($this->orthophonistes->removeElement($orthophoniste)) {
            // set the owning side to null (unless already changed)
            if ($orthophoniste->getCabinet() === $this) {
                $orthophoniste->setCabinet(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImagesCabinet(): Collection
    {
        return $this->imagesCabinet;
    }

    public function addImagesCabinet(Image $imagesCabinet): static
    {
        if (!$this->imagesCabinet->contains($imagesCabinet)) {
            $this->imagesCabinet->add($imagesCabinet);
            $imagesCabinet->setCabinet($this);
        }

        return $this;
    }

    public function removeImagesCabinet(Image $imagesCabinet): static
    {
        if ($this->imagesCabinet->removeElement($imagesCabinet)) {
            // set the owning side to null (unless already changed)
            if ($imagesCabinet->getCabinet() === $this) {
                $imagesCabinet->setCabinet(null);
            }
        }

        return $this;
    }
}
