<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Patient;
use App\Entity\Orthophoniste;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Utilisateurs simples
        for ($i = 1; $i <= 5; $i++) {
            $user = new User();
            $user->setEmail("user$i@example.com")
                ->setPassword($this->passwordHasher->hashPassword($user, 'password'))
                ->setNom($faker->lastName())
                ->setPrenom($faker->firstName())
                ->setTelephone($faker->phoneNumber())
                ->setVille($faker->city())
                ->setGenre($faker->randomElement(['Homme', 'Femme']))
                ->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($user);
        }

        // Orthophonistes
        for ($i = 1; $i <= 3; $i++) {
            $orthophoniste = new Orthophoniste();
            $orthophoniste->setEmail("ortho$i@example.com")
                ->setPassword($this->passwordHasher->hashPassword($orthophoniste, 'password'))
                ->setNom($faker->lastName())
                ->setPrenom($faker->firstName())
                ->setTelephone($faker->phoneNumber())
                ->setVille($faker->city())
                ->setGenre($faker->randomElement(['Homme', 'Femme']))
                ->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($orthophoniste);
        }

        // Patients
        for ($i = 1; $i <= 2; $i++) {
            $patient = new Patient();
            $patient->setEmail("patient$i@example.com")
                ->setPassword($this->passwordHasher->hashPassword($patient, 'password'))
                ->setNom($faker->lastName())
                ->setPrenom($faker->firstName())
                ->setTelephone($faker->phoneNumber())
                ->setVille($faker->city())
                ->setGenre($faker->randomElement(['Homme', 'Femme']))
                ->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($patient);
        }

        $manager->flush();
    }
}
