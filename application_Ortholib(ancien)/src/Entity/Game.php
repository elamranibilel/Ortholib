<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    /**
     * @var Collection<int, Score>
     */
    #[ORM\OneToMany(targetEntity: Score::class, mappedBy: 'game', orphanRemoval: true)]
    private Collection $scoresGame;

    /**
     * @var Collection<int, Level>
     */
    #[ORM\OneToMany(targetEntity: Level::class, mappedBy: 'gameLevel', orphanRemoval: true)]
    private Collection $level;

    /**
     * @var Collection<int, Question>
     */
    #[ORM\OneToMany(targetEntity: Question::class, mappedBy: 'gameQuestion', orphanRemoval: true)]
    private Collection $question;

    public function __construct()
    {
        $this->scoresGame = new ArrayCollection();
        $this->level = new ArrayCollection();
        $this->question = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
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

    /**
     * @return Collection<int, Score>
     */
    public function getScoresGame(): Collection
    {
        return $this->scoresGame;
    }

    public function addScoresGame(Score $scoresGame): static
    {
        if (!$this->scoresGame->contains($scoresGame)) {
            $this->scoresGame->add($scoresGame);
            $scoresGame->setGame($this);
        }

        return $this;
    }

    public function removeScoresGame(Score $scoresGame): static
    {
        if ($this->scoresGame->removeElement($scoresGame)) {
            // set the owning side to null (unless already changed)
            if ($scoresGame->getGame() === $this) {
                $scoresGame->setGame(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Level>
     */
    public function getLevel(): Collection
    {
        return $this->level;
    }

    public function addLevel(Level $level): static
    {
        if (!$this->level->contains($level)) {
            $this->level->add($level);
            $level->setGameLevel($this);
        }

        return $this;
    }

    public function removeLevel(Level $level): static
    {
        if ($this->level->removeElement($level)) {
            // set the owning side to null (unless already changed)
            if ($level->getGameLevel() === $this) {
                $level->setGameLevel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Question>
     */
    public function getQuestion(): Collection
    {
        return $this->question;
    }

    public function addQuestion(Question $question): static
    {
        if (!$this->question->contains($question)) {
            $this->question->add($question);
            $question->setGameQuestion($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): static
    {
        if ($this->question->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getGameQuestion() === $this) {
                $question->setGameQuestion(null);
            }
        }

        return $this;
    }
}
