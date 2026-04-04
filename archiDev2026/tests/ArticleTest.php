<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Article;

class ArticleTest extends TestCase
{
    public function testTitleSetterGetter(): void
    {
        $article = new Article();
        $article->setTitle('Mon premier article');
        $this->assertEquals('Mon premier article', $article->getTitle());
    }

    public function testIdEstNullParDefaut(): void
    {
        $article = new Article();
        $this->assertNull($article->getId());
    }

    public function testCreatedSetterGetter(): void
    {
        $article = new Article();
        $date = new \DateTime('2026-04-01');
        $article->setCreated($date);
        $this->assertEquals('2026-04-01', $article->getCreated()->format('Y-m-d'));
    }

    public function testArticleComplet(): void
    {
        $article = new Article();
        $date = new \DateTime();
        $article->setTitle('Mon premier article')
                ->setDescription('Ceci est la description de mon premier article')
                ->setCreated($date);

        $this->assertEquals('Mon premier article', $article->getTitle());
        $this->assertEquals('Ceci est la description de mon premier article', $article->getDescription());
        $this->assertEquals($date, $article->getCreated());
    }
    
}
