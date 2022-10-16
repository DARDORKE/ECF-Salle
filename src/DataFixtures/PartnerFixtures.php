<?php

namespace App\DataFixtures;

use App\Entity\Partner;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PartnerFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        for ($i = 0; $i < 100; $i++) {
            $module = new Partner();
            $faker = Factory::create('fr_FR');
            $module->setName($faker->company);
            $manager->persist($module);
        }

        $manager->flush();
    }
}
