<?php

namespace App\Remote\Secours\Entity;

use App\Repository\SpecialisationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SpecialisationRepository::class)]
class Specialisation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    /**
     * @var Collection<int, Orthophoniste>
     */
    #[ORM\OneToMany(targetEntity: Orthophoniste::class, mappedBy: 'specialisation', orphanRemoval: true)]
    private Collection $orthophoniste;

    /**
     * @var Collection<int, Patient>
     */
    #[ORM\OneToMany(targetEntity: Patient::class, mappedBy: 'specialisation', orphanRemoval: true)]
    private Collection $patient;

    public function __construct()
    {
        $this->orthophoniste = new ArrayCollection();
        $this->patient = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection<int, Orthophoniste>
     */
    public function getOrthophoniste(): Collection
    {
        return $this->orthophoniste;
    }

    public function addOrthophoniste(Orthophoniste $orthophoniste): static
    {
        if (!$this->orthophoniste->contains($orthophoniste)) {
            $this->orthophoniste->add($orthophoniste);
            $orthophoniste->setSpecialisation($this);
        }

        return $this;
    }

    public function removeOrthophoniste(Orthophoniste $orthophoniste): static
    {
        if ($this->orthophoniste->removeElement($orthophoniste)) {
            // set the owning side to null (unless already changed)
            if ($orthophoniste->getSpecialisation() === $this) {
                $orthophoniste->setSpecialisation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Patient>
     */
    public function getPatient(): Collection
    {
        return $this->patient;
    }

    public function addPatient(Patient $patient): static
    {
        if (!$this->patient->contains($patient)) {
            $this->patient->add($patient);
            $patient->setSpecialisation($this);
        }

        return $this;
    }

    public function removePatient(Patient $patient): static
    {
        if ($this->patient->removeElement($patient)) {
            // set the owning side to null (unless already changed)
            if ($patient->getSpecialisation() === $this) {
                $patient->setSpecialisation(null);
            }
        }

        return $this;
    }
}
