<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Monolog\Handler\IFTTTHandler;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategorieFixtures extends Fixture
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger=$slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create("fr_FR");
        for($i=0;$i<=4;$i++) {
            $nomCategorie=null;
            if ($i==0){
                $nomCategorie="restaurant";
            } elseif ($i==1){
                $nomCategorie="hôtel";
            } elseif ($i==2){
                $nomCategorie="gîte";
            }elseif ($i==3){
                $nomCategorie="musée";
            }elseif ($i==4){
                $nomCategorie="artisanat";
            }
            $categorie = new Categorie();
            $categorie->setNom($nomCategorie);
            $categorie->setCreatedAt($faker->dateTimeBetween('-6month'));
            $this->addReference('categorie'. $i, $categorie);
            $manager->persist($categorie);
        }

            $manager->flush();
    }
}
