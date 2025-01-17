<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PatientRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: PatientRepository::class)]
class Patient implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }
}
