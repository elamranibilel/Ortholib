<?php

namespace App\Entity;

use App\Repository\OrthophonisteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrthophonisteRepository::class)]
class Orthophoniste extends Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $nombreHeureTravail = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombreHeureTravail(): ?int
    {
        return $this->nombreHeureTravail;
    }

    public function setNombreHeureTravail(?int $nombreHeureTravail): static
    {
        $this->nombreHeureTravail = $nombreHeureTravail;

        return $this;
    }
}
