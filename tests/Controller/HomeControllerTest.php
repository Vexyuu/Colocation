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

    public function testLegal(): void
    {
        $client = static::createClient();
        
        // Test French legal route
        $client->request('GET', '/fr/legal');
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Informations Légales & RGPD');
        self::assertSelectorExists('a[href="/fr/legal"]'); // Link in footer
        
        // Test English legal route
        $client->request('GET', '/en/legal');
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Legal Information & GDPR');
        self::assertSelectorExists('a[href="/en/legal"]'); // Link in footer
    }
}

