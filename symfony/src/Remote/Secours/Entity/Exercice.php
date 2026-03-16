<?php

namespace App\Remote\Secours\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ExerciceRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: ExerciceRepository::class)]
class Exercice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(nullable: true)]
    private ?int $chronometre = null;

    #[ORM\ManyToOne(inversedBy: 'exercices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Patient $patient = null;

    #[ORM\ManyToOne(inversedBy: 'exercices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Orthophoniste $orthophoniste = null;

    /**
     * @var Collection<int, Signification>
     */
    #[ORM\ManyToMany(targetEntity: Signification::class, inversedBy: 'exercices')]
    #[ORM\JoinTable(name: 'exercice_signification')]
    private Collection $signification;

    /**
     * @var Collection<int, ResultatExercice>
     */
    #[ORM\OneToMany(targetEntity: ResultatExercice::class, mappedBy: 'exercice', orphanRemoval: true)]
    private Collection $resultatExercices;

    public function __construct()
    {
        $this->signification = new ArrayCollection();
        $this->resultatExercices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getChronometre(): ?int
    {
        return $this->chronometre;
    }

    public function setChronometre(?int $chronometre): static
    {
        $this->chronometre = $chronometre;

        return $this;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): static
    {
        $this->patient = $patient;

        return $this;
    }

    public function getOrthophoniste(): ?Orthophoniste
    {
        return $this->orthophoniste;
    }

    public function setOrthophoniste(?Orthophoniste $orthophoniste): static
    {
        $this->orthophoniste = $orthophoniste;

        return $this;
    }

    /**
     * @return Collection<int, Signification>
     */
    public function getSignification(): Collection
    {
        return $this->signification;
    }

    public function addSignification(Signification $signification): static
    {
        if (!$this->signification->contains($signification)) {
            $this->signification->add($signification);
        }

        return $this;
    }

    public function removeSignification(Signification $signification): static
    {
        $this->signification->removeElement($signification);

        return $this;
    }

    /**
     * @return Collection<int, ResultatExercice>
     */
    public function getResultatExercices(): Collection
    {
        return $this->resultatExercices;
    }

    public function addResultatExercice(ResultatExercice $resultatExercice): static
    {
        if (!$this->resultatExercices->contains($resultatExercice)) {
            $this->resultatExercices->add($resultatExercice);
            $resultatExercice->setExercice($this);
        }

        return $this;
    }

    public function removeResultatExercice(ResultatExercice $resultatExercice): static
    {
        if ($this->resultatExercices->removeElement($resultatExercice)) {
            // set the owning side to null (unless already changed)
            if ($resultatExercice->getExercice() === $this) {
                $resultatExercice->setExercice(null);
            }
        }

        return $this;
    }
}
