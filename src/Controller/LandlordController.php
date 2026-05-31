<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Appartement;
use App\Entity\Facture;
use App\Entity\Message;
use App\Entity\Quittance;
use App\Entity\User;
use App\Enum\PaymentStatus;
use App\Form\AnnonceType;
use App\Form\AppartementType;
use App\Form\FactureType;
use App\Repository\AnnonceRepository;
use App\Repository\FactureRepository;
use App\Repository\QuittanceRepository;
use App\Repository\UserRepository;
use App\Service\TantiemeCalculator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/{_locale}', requirements: ['_locale' => 'fr|en'], defaults: ['_locale' => 'fr'])]
#[IsGranted('ROLE_LANDLORD')]
class LandlordController extends AbstractController
{
    #[Route('/proprietaire', name: 'app_landlord_dashboard')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        TantiemeCalculator $tantiemeCalculator,
        SluggerInterface $slugger,
        AnnonceRepository $annonceRepository,
        FactureRepository $factureRepository,
        QuittanceRepository $quittanceRepository,
        UserRepository $userRepository
    ): Response {
        /** @var User $landlord */
        $landlord = $this->getUser();

        // 1. Chasse aux requêtes N+1 : charger les appartements avec leurs chambres, locataires et factures
        // Doctrine ramène tout en un minimum de requêtes grâce aux relations
        $appartements = $entityManager->getRepository(\App\Entity\Appartement::class)
            ->createQueryBuilder('a')
            ->leftJoin('a.chambres', 'c')
            ->addSelect('c')
            ->leftJoin('c.tenant', 't')
            ->addSelect('t')
            ->leftJoin('a.factures', 'f')
            ->addSelect('f')
            ->andWhere('a.landlord = :landlord')
            ->setParameter('landlord', $landlord)
            ->getQuery()
            ->getResult();

        // 2. Récupérer les locataires associés à ces appartements
        $locataires = [];
        foreach ($appartements as $app) {
            foreach ($app->getChambres() as $chambre) {
                if ($chambre->getTenant()) {
                    $locataires[] = $chambre->getTenant();
                }
            }
        }
        $locataires = array_unique($locataires, SORT_REGULAR);

        // 3. Récupérer les quittances ventilées pour le bailleur
        $quittances = $quittanceRepository->createQueryBuilder('q')
            ->join('q.tenant', 't')
            ->join('t.chambre', 'c')
            ->join('c.appartment', 'a')
            ->leftJoin('q.billRef', 'f')
            ->addSelect('t', 'c', 'a', 'f')
            ->andWhere('a.landlord = :landlord')
            ->setParameter('landlord', $landlord)
            ->orderBy('q.transmissionDate', 'DESC')
            ->getQuery()
            ->getResult();

        // 4. Formulaire d'annonce
        $annonce = new Annonce();
        $annonceForm = $this->createForm(AnnonceType::class, $annonce, [
            'landlord' => $landlord,
        ]);
        $annonceForm->handleRequest($request);

        if ($annonceForm->isSubmitted() && $annonceForm->isValid()) {
            $photoFile = $annonceForm->get('photoFile')->getData();

            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photoFile->guessExtension();

                $uploadsDirectory = $this->getParameter('kernel.project_dir').'/public/uploads/photos';
                if (!file_exists($uploadsDirectory)) {
                    mkdir($uploadsDirectory, 0777, true);
                }

                try {
                    $photoFile->move($uploadsDirectory, $newFilename);
                    $annonce->setPhotoFilename($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors du stockage de la photo.');
                }
            }

            $annonce->setAuthor($landlord);
            $entityManager->persist($annonce);
            $entityManager->flush();

            $this->addFlash('success', 'Votre annonce a été publiée avec succès !');
            return $this->redirectToRoute('app_landlord_dashboard');
        }

        // 5. Formulaire de facture
        $facture = new Facture();
        $factureForm = $this->createForm(FactureType::class, $facture, [
            'landlord' => $landlord,
        ]);
        $factureForm->handleRequest($request);

        if ($factureForm->isSubmitted() && $factureForm->isValid()) {
            $entityManager->persist($facture);
            $entityManager->flush();

            $this->addFlash('success', 'La facture a été ajoutée avec succès !');
            return $this->redirectToRoute('app_landlord_dashboard');
        }

        // 5b. Formulaire de création d'appartement (NEW & DYNAMIC)
        $appartementNew = new Appartement();
        $appartementForm = $this->createForm(AppartementType::class, $appartementNew);
        $appartementForm->handleRequest($request);

        if ($appartementForm->isSubmitted() && $appartementForm->isValid()) {
            $appartementNew->setLandlord($landlord);
            $entityManager->persist($appartementNew);
            $entityManager->flush();

            $this->addFlash('success', 'Le nouvel appartement a été ajouté avec succès !');
            return $this->redirectToRoute('app_landlord_dashboard');
        }

        // 5c. Charger les annonces publiées par ce bailleur (NEW & DYNAMIC)
        $landlordAnnonces = $annonceRepository->findBy(['author' => $landlord], ['createdAt' => 'DESC']);

        // 6. Gestion des messages (Messagerie direct)
        $sentMessages = $entityManager->getRepository(Message::class)->findBy(['sender' => $landlord], ['dateSent' => 'DESC']);
        $receivedMessages = $entityManager->getRepository(Message::class)->findBy(['receiver' => $landlord], ['dateSent' => 'DESC']);

        // Formulaire d'envoi de message rapide
        if ($request->isMethod('POST') && $request->request->has('send_message')) {
            $receiverId = $request->request->get('receiver_id');
            $content = $request->request->get('message_content');

            $receiver = $userRepository->find($receiverId);
            if ($receiver && !empty($content)) {
                $message = new Message();
                $message->setSender($landlord);
                $message->setReceiver($receiver);
                $message->setContent($content);
                $message->setDateSent(new \DateTime());

                $entityManager->persist($message);
                $entityManager->flush();

                $this->addFlash('success', 'Votre message a été envoyé avec succès !');
                return $this->redirectToRoute('app_landlord_dashboard');
            }
        }

        return $this->render('landlord/index.html.twig', [
            'user' => $landlord,
            'appartements' => $appartements,
            'locataires' => $locataires,
            'quittances' => $quittances,
            'annonceForm' => $annonceForm->createView(),
            'factureForm' => $factureForm->createView(),
            'appartementForm' => $appartementForm->createView(),
            'landlordAnnonces' => $landlordAnnonces,
            'sentMessages' => $sentMessages,
            'receivedMessages' => $receivedMessages,
        ]);
    }

    #[Route('/proprietaire/facture/{id}/ventilate', name: 'app_landlord_facture_ventilate')]
    public function ventilate(
        Facture $facture,
        EntityManagerInterface $entityManager,
        TantiemeCalculator $tantiemeCalculator
    ): Response {
        /** @var User $landlord */
        $landlord = $this->getUser();
        $appartement = $facture->getAppartment();

        if ($appartement->getLandlord() !== $landlord) {
            throw $this->createAccessDeniedException("Vous n'êtes pas le propriétaire de cet appartement.");
        }

        $totalSurface = $appartement->getSuperficieTotale();
        if ($totalSurface <= 0) {
            $this->addFlash('error', "La superficie totale de l'appartement doit être supérieure à 0.");
            return $this->redirectToRoute('app_landlord_dashboard');
        }

        $chambres = $appartement->getChambres();
        $ventilatedCount = 0;

        foreach ($chambres as $chambre) {
            $tenant = $chambre->getTenant();
            if ($tenant) {
                // Calcul du tantième pour la chambre
                $tantieme = $tantiemeCalculator->calculateTantieme($chambre->getSurface(), $totalSurface);
                // Calcul de la quote-part de charges
                $chargeShare = $tantiemeCalculator->calculateShare($facture->getTotalAmount(), $tantieme);

                // Loyer de base = surface * 25€ (Green IT / Modèle éco)
                $rentWithoutCharges = $chambre->getSurface() * 25.0;

                // Montant total = Loyer + Quote-part de charges
                $rentWithCharges = $rentWithoutCharges + $chargeShare;

                // Créer la quittance ventilée
                $quittance = new Quittance();
                $quittance->setTenant($tenant);
                $quittance->setBillRef($facture);
                $quittance->setRentWithoutCharges($rentWithoutCharges);
                $quittance->setRentWithChargesPercent($rentWithCharges); // Total due
                $quittance->setTransmissionDate(new \DateTime());
                $quittance->setPaymentStatus(PaymentStatus::PENDING);

                $entityManager->persist($quittance);
                $ventilatedCount++;
            }
        }

        if ($ventilatedCount > 0) {
            $entityManager->flush();
            $this->addFlash('success', sprintf('Charges ventilées avec succès ! %d quittances de loyer ont été générées.', $ventilatedCount));
        } else {
            $this->addFlash('warning', "Aucun locataire n'occupe actuellement cet appartement pour ventiler la facture.");
        }

        return $this->redirectToRoute('app_landlord_dashboard');
    }

    #[Route('/proprietaire/quittance/{id}/pay', name: 'app_landlord_quittance_pay')]
    public function markAsPaid(Quittance $quittance, EntityManagerInterface $entityManager): Response
    {
        /** @var User $landlord */
        $landlord = $this->getUser();
        $appartement = $quittance->getTenant()->getChambre()->getAppartment();

        if ($appartement->getLandlord() !== $landlord) {
            throw $this->createAccessDeniedException("Accès non autorisé.");
        }

        $quittance->setPaymentStatus(PaymentStatus::PAID);
        $entityManager->flush();

        $this->addFlash('success', 'La quittance a été marquée comme payée avec succès ! Le locataire peut désormais la télécharger.');
        return $this->redirectToRoute('app_landlord_dashboard');
    }
}
