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
        $faker = Factory::create("fr_FR");

        for($i=0;$i<=10;$i++) {
            $user = new User();
            $user->setEmail($faker->email())
                ->setNom($faker->lastName())
                ->setPrenom($faker->firstName())
                ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                ->setActif($faker->boolean())
                ->setPseudo($faker->name())
                ->setPassword(hash('sha512','testtest'))
            ->setRoles(array('ROLE_USER'));
            $manager->persist($user);

        }
        $manager->flush();
    }
}
