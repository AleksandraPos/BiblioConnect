<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        // Verifie que la reponse est 100 ok
        $this->assertResponseIsSuccessful();
        // Verifie qu'un H1 existe
        $this->assertSelectorExists('h1');
        // Verifie le contenu du h1
        $this->assertSelectorTextContains('h1', 'Hello World');
    }
}
