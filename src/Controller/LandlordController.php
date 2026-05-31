<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/{_locale}', requirements: ['_locale' => 'fr|en'], defaults: ['_locale' => 'fr'])]
#[IsGranted('ROLE_LANDLORD')]
class LandlordController extends AbstractController
{
    #[Route('/proprietaire', name: 'app_landlord_dashboard')]
    public function index(): Response
    {
        return $this->render('landlord/index.html.twig', [
            'user' => $this->getUser(),
        ]);
    }
}
