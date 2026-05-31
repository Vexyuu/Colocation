<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class SecurityControllerTest extends WebTestCase
{
    public function testRegisterAndLoginFlow(): void
    {
        $client = static::createClient();

        // 1. Test registration of a Tenant
        $crawler = $client->request('GET', '/fr/register');
        self::assertResponseIsSuccessful();

        $form = $crawler->selectButton('Créer mon compte')->form([
            'registration_form[firstName]' => 'TestTenant',
            'registration_form[lastName]' => 'User',
            'registration_form[email]' => 'test_tenant_' . uniqid() . '@colive.fr',
            'registration_form[role]' => 'ROLE_TENANT',
            'registration_form[plainPassword]' => 'SecurePass123!',
        ]);

        $client->submit($form);
        // Successful registration auto-logins and redirects
        self::assertResponseRedirects();
        $client->followRedirect(); // Redirect to login-success
        self::assertResponseRedirects('/fr/locataire');
        $client->followRedirect(); // Redirect to /fr/locataire
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Espace Locataire');
    }

    public function testRegistrationLandlordFlow(): void
    {
        $client = static::createClient();

        // 2. Test registration of a Landlord
        $crawler = $client->request('GET', '/fr/register');
        self::assertResponseIsSuccessful();

        $form = $crawler->selectButton('Créer mon compte')->form([
            'registration_form[firstName]' => 'TestLandlord',
            'registration_form[lastName]' => 'User',
            'registration_form[email]' => 'test_landlord_' . uniqid() . '@colive.fr',
            'registration_form[role]' => 'ROLE_LANDLORD',
            'registration_form[plainPassword]' => 'SecurePass123!',
        ]);

        $client->submit($form);
        self::assertResponseRedirects();
        $client->followRedirect();
        self::assertResponseRedirects('/fr/proprietaire');
        $client->followRedirect();
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Espace Propriétaire');
    }

    public function testAccessControlRestrictions(): void
    {
        $client = static::createClient();

        // Guest accessing protected spaces -> Redirect to login
        $client->request('GET', '/fr/locataire');
        self::assertResponseRedirects('/fr/login');

        $client->request('GET', '/fr/proprietaire');
        self::assertResponseRedirects('/fr/login');
    }
}
