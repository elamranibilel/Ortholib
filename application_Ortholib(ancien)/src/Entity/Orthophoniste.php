<?php

namespace App\Entity;

use App\Repository\OrthophonisteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrthophonisteRepository::class)]
class Orthophoniste extends User
{

    #[ORM\Column(nullable: true)]
    private ?int $nombreHeureTravail = null;

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
