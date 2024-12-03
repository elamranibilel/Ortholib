<?php

namespace App\Entity;

use App\Repository\PatientRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PatientRepository::class)]
class Patient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $niveauLangue = null;

    #[ORM\Column(length: 255)]
    private ?string $difficulteTest = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $difficulteRencontrees = null;

    #[ORM\Column(length: 255)]
    private ?string $niveauApprentissage = null;

    #[ORM\Column(length: 255)]
    private ?string $deficient = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNiveauLangue(): ?string
    {
        return $this->niveauLangue;
    }

    public function setNiveauLangue(string $niveauLangue): static
    {
        $this->niveauLangue = $niveauLangue;

        return $this;
    }

    public function getDifficulteTest(): ?string
    {
        return $this->difficulteTest;
    }

    public function setDifficulteTest(string $difficulteTest): static
    {
        $this->difficulteTest = $difficulteTest;

        return $this;
    }

    public function getDifficulteRencontrees(): ?string
    {
        return $this->difficulteRencontrees;
    }

    public function setDifficulteRencontrees(?string $difficulteRencontrees): static
    {
        $this->difficulteRencontrees = $difficulteRencontrees;

        return $this;
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

    public function getDeficient(): ?string
    {
        return $this->deficient;
    }

    public function setDeficient(string $deficient): static
    {
        $this->deficient = $deficient;

        return $this;
    }
}
