<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookTest extends WebTestCase
{
    public function testHomePage(): void
{
    $client = static::createClient();
    $crawler = $client->request('GET', '/');

    $this->assertResponseIsSuccessful();
    $this->assertSelectorTextContains('h1', 'Catalogue');
}
}
