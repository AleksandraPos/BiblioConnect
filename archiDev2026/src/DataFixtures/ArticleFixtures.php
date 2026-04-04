<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i=1; $i <=20; $i++){
            $article = new Article;
            $article->setTitle('Article '. $i);
            $article->setDescription('Description '. $i);
            $article->setCreated(new \DateTime());
            $manager->persist($article);
        }
    
        $manager->flush();
    }
}
