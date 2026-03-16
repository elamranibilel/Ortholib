<?php

namespace App\Entity;

use App\Repository\SeancesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SeancesRepository::class)]
class Seances
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'seances')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Orthophoniste $orthophoniste = null;

    #[ORM\ManyToOne(inversedBy: 'seances')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Patient $patient = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateHeureDebut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateHeureFin = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Choice(['présentiel', 'visioconférence'])]
    private ?string $mode = null;

    #[ORM\Column(type: 'string', length: 8, nullable: false)]
    private ?string $duree = '00:00:00';

    #[ORM\Column(type: Types::TEXT)]
    private ?string $exercices = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): static
    {
        $this->patient = $patient;

        return $this;
    }

    public function getDateHeureDebut(): ?\DateTimeInterface
    {
        return $this->dateHeureDebut;
    }

    public function setDateHeureDebut(\DateTimeInterface $dateHeureDebut): static
    {
        $this->dateHeureDebut = $dateHeureDebut;

        return $this;
    }

    public function getDateHeureFin(): ?\DateTimeInterface
    {
        return $this->dateHeureFin;
    }

    public function setDateHeureFin(\DateTimeInterface $dateHeureFin): static
    {
        $this->dateHeureFin = $dateHeureFin;

        return $this;
    }

    public function getMode(): ?string
    {
        return $this->mode;
    }

    public function setMode(?string $mode): static
    {
        $this->mode = $mode;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->dateHeureDebut->diff($this->dateHeureFin)->h;
    }

    public function setDuree(int $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    public static function calculerTotalHeures(array $seances): int
    {
        $total = 0;
        foreach ($seances as $seance) {
            $total += $seance->getDuree();
        }
        return $total;
    }

    public static function verifierLimiteHeures(array $seances): bool
    {
        return self::calculerTotalHeures($seances) <= 35;
    }

    public function getExercices(): ?string
    {
        return $this->exercices;
    }

    public function setExercices(string $exercices): static
    {
        $this->exercices = $exercices;

        return $this;
    }
}
