<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $roles = ['ROLE_USER', 'ROLE_PARTNER', 'ROLE_ADMIN'];

        for ($i = 0; $i < 50; $i++) {
            $user = new User();
            $faker = Factory::create();
            $user->setEmail($faker->email)
                ->setEnabled(true)
                ->setPassword($faker->password)
                ->setRoles([array_rand($roles), 1]);

            $manager->persist($user);
        }
        $manager->flush();
    }
}
