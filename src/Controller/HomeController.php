<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home_redirect')]
    public function indexRedirect(): Response
    {
        return $this->redirectToRoute('app_home', ['_locale' => 'fr']);
    }

    #[Route('/{_locale}/', name: 'app_home', requirements: ['_locale' => 'fr|en'])]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
