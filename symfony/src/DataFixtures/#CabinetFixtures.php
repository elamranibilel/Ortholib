<?php

namespace App\DataFixtures;

use App\Entity\Cabinet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CabinetFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 5; $i++) {
            $cabinet = new Cabinet();
            $cabinet->setNom($faker->lastName())
                ->setAdresse($faker->address())
                ->setTelephone($faker->phoneNumber())
            ;
            $manager->persist($cabinet);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
