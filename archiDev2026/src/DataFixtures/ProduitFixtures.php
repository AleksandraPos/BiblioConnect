<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Produit;

class ProduitFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i=1; $i <=20; $i++){
            $produit = new Produit;
            $produit->setTitle('Produit '. $i);
            $produit->setDescription('Description '. $i);
            $produit->setCreated(new \DateTime());
            $produit->setPrix(140);
            $manager->persist($produit);
        }
    
        $manager->flush();
    }
}
