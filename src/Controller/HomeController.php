<?php

namespace App\Controller;

use App\Repository\AnnonceRepository;
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

    #[Route('/{_locale}/', name: 'app_home', requirements: ['_locale' => 'fr|en'], defaults: ['_locale' => 'fr'])]
    public function index(AnnonceRepository $annonceRepository): Response
    {
        // 1. Chasse aux requêtes N+1 : charger les annonces avec l'appartement et l'auteur
        $annonces = $annonceRepository->createQueryBuilder('a')
            ->join('a.appartement', 'ap')
            ->join('a.author', 'au')
            ->addSelect('ap', 'au')
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery()
            ->getResult();

        // 2. Rendu de la vue publique
        $response = $this->render('home/index.html.twig', [
            'annonces' => $annonces,
        ]);

        // 3. Green IT : Activation du cache HTTP public (S-MaxAge) pendant 60 secondes
        // Le serveur n'interrogera plus la base de données pour les requêtes successives dans cette minute.
        $response->setPublic();
        $response->setSharedMaxAge(60);
        $response->headers->addCacheControlDirective('must-revalidate', true);

        return $response;
    }
}
