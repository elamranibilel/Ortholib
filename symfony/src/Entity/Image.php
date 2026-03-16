<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    /*     #[Vich\UploadableField(mapping: 'images_directory')]
 */
    #[ORM\ManyToOne(inversedBy: 'imagesCabinet')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Cabinet $cabinet = null;

    #[ORM\ManyToOne(inversedBy: 'imagesOrthophoniste')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Orthophoniste $orthophoniste = null;

    #[ORM\ManyToOne(inversedBy: 'imagesPatient')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Patient $patient = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

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
}
