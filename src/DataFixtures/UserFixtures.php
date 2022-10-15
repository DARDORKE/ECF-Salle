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
        $roles = ['STRUCTURE'=>'ROLE_STRUCTURE', 'PARTNER' => 'ROLE_PARTNER', 'ADMIN' => 'ROLE_ADMIN'];
        $enabled = ['1' => true, '2' => false];


        for ($i = 0; $i < 50; $i++) {
            $user = new User();
            $faker = Factory::create();
            $randomRole = array_rand($roles);
            $randomEnabled = array_rand($enabled);


            $user->setEmail($faker->email)
                ->setEnabled($enabled[$randomEnabled])
                ->setPassword($faker->password)
                ->setRoles([$roles[$randomRole]]);


            $manager->persist($user);
        }
        $manager->flush();
    }
}
