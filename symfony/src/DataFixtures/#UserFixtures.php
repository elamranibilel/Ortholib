<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Cabinet;
use App\Entity\Patient;
use App\Entity\Exercice;
use App\Entity\Commentaire;
use App\Entity\Orthophoniste;
use App\Entity\Signification;
use App\Entity\Administrateur;
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

        $specialisation = [
            'Troubles auditifs et surdité',
            'Troubles cognitifs et communication alternative',
            'Troubles du langage écrit'
        ];

        $images = [
            'audition.webp',
            'cabinet1.png',
        ];

        for ($i = 0; $i < 5; $i++) {
            $admin = new Administrateur();
            $admin->setEmail('admin' . $i . '@example.com')
                ->setPassword($this->passwordHasher->hashPassword($admin, 'admin1234'))
                ->setRoles(['ROLE_ADMIN'])
                ->setNom('Admin')
                ->setPrenom('Super')
                ->setTelephone($faker->phoneNumber())
                ->setVille($faker->city())
                ->setGenre('Homme')
                ->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($admin);
        }

        for ($i = 0; $i < 5; $i++) {
            $cabinet = new Cabinet();
            $cabinets = $manager->getRepository(Cabinet::class)->findAll();
            $cabinet->setNom($faker->lastName())
                ->setAdresse($faker->address())
                ->setTelephone($faker->phoneNumber())
            ;
            $image = new Image();
            $image->setName($faker->randomElement($images));
            $manager->persist($image);

            $cabinet->addImagesCabinet($image);
            $manager->persist($cabinet);
        }

        for ($i = 0; $i < 5; $i++) {
            $orthophoniste = new Orthophoniste();
            $orthophonistes = $manager->getRepository(Orthophoniste::class)->findAll();
            $orthophoniste->setEmail('ortho' . $i . '@example.com')
                ->setPassword($this->passwordHasher->hashPassword($orthophoniste, 'ortho1234'))
                ->setRoles(['ROLE_ORTHO'])
                ->setNom($faker->lastName())
                ->setPrenom($faker->firstName())
                ->setTelephone($faker->phoneNumber())
                ->setVille($faker->city())
                ->setGenre($faker->randomElement(['Homme', 'Femme']))
                ->setCreatedAt(new \DateTimeImmutable())
                ->setNombreHeureTravail(35)
                ->setCabinet($cabinet)
                ->setSpecialisation($faker->randomElement($specialisation));

            $image = new Image();
            $image->setName($faker->randomElement($images));
            $manager->persist($image);

            $orthophoniste->addImagesOrthophoniste($image);

            $manager->persist($orthophoniste);
        }

        for ($i = 0; $i < 5; $i++) {
            $patient = new Patient();
            $patient->setEmail('patient' . $i . '@example.com')
                ->setPassword($this->passwordHasher->hashPassword($patient, 'patient1234'))
                ->setRoles(['ROLE_PATIENT'])
                ->setNom($faker->lastName())
                ->setPrenom($faker->firstName())
                ->setTelephone($faker->phoneNumber())
                ->setVille($faker->city())
                ->setGenre($faker->randomElement(['Homme', 'Femme']))
                ->setCreatedAt(new \DateTimeImmutable())
                ->setNiveauLangue('Débutant')
                ->setDifficulteTest('Lecture')
                ->setNiveauApprentissage('A1')
                ->setDeficient('Auditif')
                ->setDifficulteRencontrees(['Compréhension', 'Prononciation'])
                ->setChoixOrtho(($orthophoniste))
                ->setIsConfirmed(false);

            $image = new Image();
            $image->setName($faker->randomElement($images));
            $manager->persist($image);

            $patient->addImagesPatient($image);

            $manager->persist($patient);
        }

        for ($i = 0; $i < 7; $i++) {
            $signification = new Signification();
            $signification->setMots($faker->word())
                ->setDefinition($faker->sentence());
            $manager->persist($signification);
        }

        for ($i = 0; $i < 5; $i++) {
            $exercice = new Exercice();
            $exercice
                ->setType($faker->randomElement(['Vocabulaire', 'Mémoire', 'Orthographe']))
                ->setPatient($patient)
                ->setOrthophoniste($orthophoniste)
                /* ->setChronometre($faker->numberBetween(1, 10)) */;
            $manager->persist($exercice);
        }

        for ($i = 0; $i < 9; $i++) {
            $commentaire = new Commentaire();
            $commentaire->setTexte($faker->sentence())
                ->setAuteur($faker->randomElement([$admin, $orthophoniste, $patient]))
                ->setDestinataire($faker->randomElement([$admin, $orthophoniste, $patient]))
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-6 months', 'now')));

            $manager->persist($commentaire);
        }
        $manager->flush();
    }
}
