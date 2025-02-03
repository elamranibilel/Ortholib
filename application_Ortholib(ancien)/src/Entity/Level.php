<?php

namespace App\Entity;

use App\Repository\LevelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LevelRepository::class)]
class Level
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $difficulty = null;

    #[ORM\Column(nullable: true)]
    private ?int $timeLimit = null;

    #[ORM\Column]
    private ?int $successThreshold = null;

    /**
     * @var Collection<int, Score>
     */
    #[ORM\OneToMany(targetEntity: Score::class, mappedBy: 'level', orphanRemoval: true)]
    private Collection $scoresLevel;

    #[ORM\ManyToOne(inversedBy: 'level')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Game $gameLevel = null;

    public function __construct()
    {
        $this->scoresLevel = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDifficulty(): ?string
    {
        return $this->difficulty;
    }

    public function setDifficulty(string $difficulty): static
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getTimeLimit(): ?int
    {
        return $this->timeLimit;
    }

    public function setTimeLimit(?int $timeLimit): static
    {
        $this->timeLimit = $timeLimit;

        return $this;
    }

    public function getSuccessThreshold(): ?int
    {
        return $this->successThreshold;
    }

    public function setSuccessThreshold(int $successThreshold): static
    {
        $this->successThreshold = $successThreshold;

        return $this;
    }

    /**
     * @return Collection<int, Score>
     */
    public function getScoresLevel(): Collection
    {
        return $this->scoresLevel;
    }

    public function addScoresLevel(Score $scoresLevel): static
    {
        if (!$this->scoresLevel->contains($scoresLevel)) {
            $this->scoresLevel->add($scoresLevel);
            $scoresLevel->setLevel($this);
        }

        return $this;
    }

    public function removeScoresLevel(Score $scoresLevel): static
    {
        if ($this->scoresLevel->removeElement($scoresLevel)) {
            // set the owning side to null (unless already changed)
            if ($scoresLevel->getLevel() === $this) {
                $scoresLevel->setLevel(null);
            }
        }

        return $this;
    }

    public function getGameLevel(): ?Game
    {
        return $this->gameLevel;
    }

    public function setGameLevel(?Game $gameLevel): static
    {
        $this->gameLevel = $gameLevel;

        return $this;
    }
}
