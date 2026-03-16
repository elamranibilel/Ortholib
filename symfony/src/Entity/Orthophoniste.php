<?php

namespace App\Entity;

use App\Repository\OrthophonisteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrthophonisteRepository::class)]
class Orthophoniste extends User
{
    #[ORM\Column(nullable: true)]
    private ?int $nombreHeureTravail = null;

    /**
     * @var Collection<int, Patient>
     */
    #[ORM\OneToMany(targetEntity: Patient::class, mappedBy: 'choixOrtho', orphanRemoval: true)]
    private Collection $patients;

    #[ORM\ManyToOne(inversedBy: 'orthophonistes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cabinet $cabinet = null;

    /**
     * @var Collection<int, Image>
     */
    #[ORM\OneToMany(targetEntity: Image::class, mappedBy: 'orthophoniste', orphanRemoval: true, cascade: ['persist'])]
    private Collection $imagesOrthophoniste;

    /**
     * @var Collection<int, Seances>
     */
    #[ORM\OneToMany(targetEntity: Seances::class, mappedBy: 'orthophoniste', orphanRemoval: true)]
    private Collection $seances;

    /**
     * @var Collection<int, Exercice>
     */
    #[ORM\OneToMany(targetEntity: Exercice::class, mappedBy: 'orthophoniste', orphanRemoval: true)]
    private Collection $exercices;

    #[ORM\ManyToOne(inversedBy: 'orthophoniste')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Specialisation $specialisation = null;

    public function __construct()
    {
        parent::__construct();
        $this->patients = new ArrayCollection();
        $this->imagesOrthophoniste = new ArrayCollection();
        $this->seances = new ArrayCollection();
        $this->exercices = new ArrayCollection();
    }

    /*     #[ORM\Column]
    private array $roles = []; */

    public function getNombreHeureTravail(): ?int
    {
        return $this->nombreHeureTravail;
    }

    public function setNombreHeureTravail(?int $nombreHeureTravail): static
    {
        $this->nombreHeureTravail = $nombreHeureTravail;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = parent::getRoles(); // Récupère les rôles de User
        $roles[] = 'ROLE_ORTHO';     // Ajoute ROLE_ORTHO
        return array_unique($roles);
    }

    /* public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    } */

    /**
     * @return Collection<int, Patient>
     */
    public function getPatients(): Collection
    {
        return $this->patients;
    }

    public function addPatient(Patient $patient): static
    {
        if (!$this->patients->contains($patient)) {
            $this->patients->add($patient);
            $patient->setChoixOrtho($this);
        }

        return $this;
    }

    public function removePatient(Patient $patient): static
    {
        if ($this->patients->removeElement($patient)) {
            // set the owning side to null (unless already changed)
            if ($patient->getChoixOrtho() === $this) {
                $patient->setChoixOrtho(null);
            }
        }

        return $this;
    }

    public function getCabinet(): ?Cabinet
    {
        return $this->cabinet;
    }

    public function setCabinet(?Cabinet $cabinet): static
    {
        $this->cabinet = $cabinet;

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImagesOrthophoniste(): Collection
    {
        return $this->imagesOrthophoniste;
    }

    public function addImagesOrthophoniste(Image $imagesOrthophoniste): static
    {
        if (!$this->imagesOrthophoniste->contains($imagesOrthophoniste)) {
            $this->imagesOrthophoniste->add($imagesOrthophoniste);
            $imagesOrthophoniste->setOrthophoniste($this);
        }

        return $this;
    }

    public function removeImagesOrthophoniste(Image $imagesOrthophoniste): static
    {
        if ($this->imagesOrthophoniste->removeElement($imagesOrthophoniste)) {
            // set the owning side to null (unless already changed)
            if ($imagesOrthophoniste->getOrthophoniste() === $this) {
                $imagesOrthophoniste->setOrthophoniste(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Seances>
     */
    public function getSeances(): Collection
    {
        return $this->seances;
    }

    public function addSeance(Seances $seance): static
    {
        if (!$this->seances->contains($seance)) {
            $this->seances->add($seance);
            $seance->setOrthophoniste($this);
        }

        return $this;
    }

    public function removeSeance(Seances $seance): static
    {
        if ($this->seances->removeElement($seance)) {
            // set the owning side to null (unless already changed)
            if ($seance->getOrthophoniste() === $this) {
                $seance->setOrthophoniste(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Exercice>
     */
    public function getExercices(): Collection
    {
        return $this->exercices;
    }

    public function addExercice(Exercice $exercice): static
    {
        if (!$this->exercices->contains($exercice)) {
            $this->exercices->add($exercice);
            $exercice->setOrthophoniste($this);
        }

        return $this;
    }

    public function removeExercice(Exercice $exercice): static
    {
        if ($this->exercices->removeElement($exercice)) {
            // set the owning side to null (unless already changed)
            if ($exercice->getOrthophoniste() === $this) {
                $exercice->setOrthophoniste(null);
            }
        }

        return $this;
    }

    public function getSpecialisation(): ?Specialisation
    {
        return $this->specialisation;
    }

    public function setSpecialisation(?Specialisation $specialisation): static
    {
        $this->specialisation = $specialisation;

        return $this;
    }
}
