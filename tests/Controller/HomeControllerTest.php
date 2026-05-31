<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class HomeControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');
        
        self::assertResponseRedirects('/fr/');
        $crawler = $client->followRedirect();

        self::assertResponseIsSuccessful();
        
        // SEO Assertions
        self::assertSelectorTextContains('title', 'CoLive');
        self::assertSelectorExists('meta[name="description"]');
        self::assertSelectorExists('header.navbar');
        self::assertSelectorExists('main');
        self::assertSelectorExists('section#vision');
        self::assertSelectorExists('section#fonctionnalites');
        self::assertSelectorExists('section#faq');
        self::assertSelectorExists('footer.footer');
    }
}
