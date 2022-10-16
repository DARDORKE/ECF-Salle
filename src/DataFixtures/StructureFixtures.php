<?php

namespace App\DataFixtures;

use App\Entity\Structure;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class StructureFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        for ($i = 0; $i < 100; $i++) {
            $structure = new Structure();
            $faker = Factory::create('fr_FR');
            $structure->setAddress($faker->address);

            $manager->persist($structure);
        }

        $manager->flush();
    }
}
