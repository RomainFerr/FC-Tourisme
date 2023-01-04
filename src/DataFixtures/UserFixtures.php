<?php

namespace App\DataFixtures;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixtures extends Fixture
{

    private UserPasswordHasherInterface $hasher;

    /**
     * @param UserPasswordHasherInterface $hasher
     */
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

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
                ->setRoles(array('ROLE_USER'));
            $hashPW = $this->hasher->hashPassword(
                $user,
            'password');
            $user->setPassword($hashPW);

            $manager->persist($user);

        }
        $manager->flush();
    }
}
