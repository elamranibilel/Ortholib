<?php

namespace App\Entity;

use App\Repository\PatientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PatientRepository::class)]
class Patient extends User /* implements UserInterface, PasswordAuthenticatedUserInterface */
{
    #[ORM\Column(length: 255)]
    private ?string $niveauApprentissage = null;

    #[ORM\Column]
    private array $difficulteRencontrees = [];

    #[ORM\ManyToOne(inversedBy: 'patients')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Orthophoniste $choixOrtho = null;

    /**
     * @var Collection<int, Image>
     */
    #[ORM\OneToMany(targetEntity: Image::class, mappedBy: 'patient', orphanRemoval: true, cascade: ['persist'])]
    private Collection $imagesPatient;

    /**
     * @var Collection<int, Seances>
     */
    #[ORM\OneToMany(targetEntity: Seances::class, mappedBy: 'patient', orphanRemoval: true)]
    private Collection $seances;

    /**
     * @var Collection<int, Exercice>
     */
    #[ORM\OneToMany(targetEntity: Exercice::class, mappedBy: 'patient', orphanRemoval: true)]
    private Collection $exercices;

    /**
     * @var Collection<int, ResultatExercice>
     */
    #[ORM\OneToMany(targetEntity: ResultatExercice::class, mappedBy: 'patient', orphanRemoval: true)]
    private Collection $resultatExercices;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private ?bool $isConfirmed = null;

    #[ORM\ManyToOne(inversedBy: 'patient')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Specialisation $specialisation = null;

    public function __construct()
    {
        parent::__construct();
        $this->imagesPatient = new ArrayCollection();
        $this->seances = new ArrayCollection();
        $this->exercices = new ArrayCollection();
        $this->resultatExercices = new ArrayCollection();
    }

    public function getNiveauApprentissage(): ?string
    {
        return $this->niveauApprentissage;
    }

    public function setNiveauApprentissage(string $niveauApprentissage): static
    {
        $this->niveauApprentissage = $niveauApprentissage;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = parent::getRoles(); // Récupère les rôles de User
        $roles[] = 'ROLE_PATIENT';   // Ajoute ROLE_PATIENT
        return array_unique($roles);
    }

    /* public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    } */

    public function getDifficulteRencontrees(): array
    {
        return $this->difficulteRencontrees;
    }

    public function setDifficulteRencontrees(array $difficulteRencontrees): static
    {
        $this->difficulteRencontrees = $difficulteRencontrees;

        return $this;
    }

    public function getChoixOrtho(): ?Orthophoniste
    {
        return $this->choixOrtho;
    }

    public function setChoixOrtho(?Orthophoniste $choixOrtho): static
    {
        $this->choixOrtho = $choixOrtho;

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImagesPatient(): Collection
    {
        return $this->imagesPatient;
    }

    public function addImagesPatient(Image $imagesPatient): static
    {
        if (!$this->imagesPatient->contains($imagesPatient)) {
            $this->imagesPatient->add($imagesPatient);
            $imagesPatient->setPatient($this);
        }

        return $this;
    }

    public function removeImagesPatient(Image $imagesPatient): static
    {
        if ($this->imagesPatient->removeElement($imagesPatient)) {
            // set the owning side to null (unless already changed)
            if ($imagesPatient->getPatient() === $this) {
                $imagesPatient->setPatient(null);
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
            $seance->setPatient($this);
        }

        return $this;
    }

    public function removeSeance(Seances $seance): static
    {
        if ($this->seances->removeElement($seance)) {
            // set the owning side to null (unless already changed)
            if ($seance->getPatient() === $this) {
                $seance->setPatient(null);
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
            $exercice->setPatient($this);
        }

        return $this;
    }

    public function removeExercice(Exercice $exercice): static
    {
        if ($this->exercices->removeElement($exercice)) {
            // set the owning side to null (unless already changed)
            if ($exercice->getPatient() === $this) {
                $exercice->setPatient(null);
            }
        }

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
            $resultatExercice->setPatient($this);
        }

        return $this;
    }

    public function removeResultatExercice(ResultatExercice $resultatExercice): static
    {
        if ($this->resultatExercices->removeElement($resultatExercice)) {
            // set the owning side to null (unless already changed)
            if ($resultatExercice->getPatient() === $this) {
                $resultatExercice->setPatient(null);
            }
        }

        return $this;
    }

    public function isConfirmed(): ?bool
    {
        return $this->isConfirmed;
    }

    public function setIsConfirmed(bool $isConfirmed): static
    {
        $this->isConfirmed = $isConfirmed;

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
