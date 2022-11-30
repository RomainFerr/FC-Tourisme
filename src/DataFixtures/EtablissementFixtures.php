<?php

namespace App\DataFixtures;

use App\Entity\Etablissement;
use App\Entity\Ville;
use App\Repository\VilleRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class EtablissementFixtures extends Fixture implements DependentFixtureInterface
{
    private VilleRepository $villeRepository;
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger, VilleRepository $villeRepository)
    {
        $this->slugger=$slugger;
        $this->villeRepository=$villeRepository;
    }

    public function getDependencies()
    {
        return [
            CategorieFixtures::class,
        ];
    }
    public function load(ObjectManager $manager ): void
    {
        $faker = Factory::create("fr_FR");
        for ($i=0;$i<100;$i++) {
            $etablissement = new Etablissement();
            $etablissement->setNom($faker->words(5,true));
            $etablissement->setDescription($faker->paragraphs(3,true));
            $etablissement->setSlug($this->slugger->slug($etablissement->getNom())->lower());

            $etablissement->setNumeroTelephone($faker->phoneNumber());
            $etablissement->setAdresse($faker->address());
            $etablissement->setAdresseMail($faker->email());
            $etablissement->setActif($faker->boolean());
            $etablissement->setAccueil($faker->boolean());

            $etablissement->setVille($this->villeRepository->findOneBy(['id'=>$faker->numberBetween(1,1908)]));

            $etablissement->setCreatedAt($faker->dateTimeBetween('-6month'));
            $refCategorie = $faker->numberBetween(0,4);
            $etablissement->addCategorie($this->getReference("categorie".$refCategorie));
            $manager->persist($etablissement);
        }

        $manager->flush();
    }
}
