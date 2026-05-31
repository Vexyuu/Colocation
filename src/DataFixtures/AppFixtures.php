<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Appartement;
use App\Entity\Chambre;
use App\Entity\Facture;
use App\Enum\PaymentStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        //Proprio
        $landlord = new User();
        $landlord->setEmail('proprietaire@colive.fr');
        $landlord->setFirstName('Jean');
        $landlord->setLastName('Dupond');
        $landlord->setRoles(['ROLE_LANDLORD']);
        $landlord->setPassword($this->passwordHasher->hashPassword($landlord, 'password'));
        $manager->persist($landlord);

        //Appart
        $appartement = new Appartement();
        $appartement->setNom('Coloc Éco-Centre');
        $appartement->setAdresse('42 Rue de la Transition, Paris');
        $appartement->setSuperficieTotale(100.0);
        $appartement->setLandlord($landlord);
        $manager->persist($appartement);

        //Colocs
        $donneesChambres = [
            ['nomUser' => 'Alex', 'prenomUser' => 'A', 'email' => 'alex@colive.fr', 'nomChambre' => 'Chambre A', 'surface' => 15.0],
            ['nomUser' => 'Blake', 'prenomUser' => 'B', 'email' => 'blake@colive.fr', 'nomChambre' => 'Chambre B', 'surface' => 12.0],
            ['nomUser' => 'Charlie', 'prenomUser' => 'C', 'email' => 'charlie@colive.fr', 'nomChambre' => 'Chambre C', 'surface' => 18.0],
        ];

        foreach ($donneesChambres as $data) {
            // Création du locataire
            $tenant = new User();
            $tenant->setEmail($data['email']);
            $tenant->setFirstName($data['prenomUser']);
            $tenant->setLastName($data['nomUser']);
            $tenant->setRoles(['ROLE_TENANT']);
            $tenant->setPassword($this->passwordHasher->hashPassword($tenant, 'password'));
            $manager->persist($tenant);

            // Création de sa chambre liée à l'appartement et au locataire
            $chambre = new Chambre();
            $chambre->setName($data['nomChambre']);
            $chambre->setSurface($data['surface']);
            $chambre->setAppartment($appartement);
            $chambre->setTenant($tenant);
            $manager->persist($chambre);
        }

        // Facture globale d'elec
        $facture = new Facture();
        $facture->setTypeOfCharge('Électricité');
        $facture->setTotalAmount(200.0);
        $facture->setBillDate(new \DateTime());
        $facture->setAppartment($appartement);
        $manager->persist($facture);

        // envoi en base de données SQLite
        $manager->flush();
    }
}