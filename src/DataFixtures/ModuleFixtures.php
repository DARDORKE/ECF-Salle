<?php

namespace App\DataFixtures;

use App\Entity\Module;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ModuleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $module = new Module();
            $faker = Factory::create();
            $module->setName($faker->title);

            $manager->persist($module);
        }
        $manager->flush();
    }
}
