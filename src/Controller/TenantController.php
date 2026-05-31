<?php

namespace App\Controller;

use App\Entity\Chores;
use App\Entity\Message;
use App\Entity\Quittance;
use App\Entity\User;
use App\Enum\ChoresStatus;
use App\Enum\PaymentStatus;
use App\Repository\ChoresRepository;
use App\Repository\MessageRepository;
use App\Repository\QuittanceRepository;
use App\Service\TantiemeCalculator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/{_locale}', requirements: ['_locale' => 'fr|en'], defaults: ['_locale' => 'fr'])]
#[IsGranted('ROLE_TENANT')]
class TenantController extends AbstractController
{
    #[Route('/locataire', name: 'app_tenant_dashboard')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        TantiemeCalculator $tantiemeCalculator,
        QuittanceRepository $quittanceRepository,
        ChoresRepository $choresRepository,
        MessageRepository $messageRepository
    ): Response {
        /** @var User $tenant */
        $tenant = $this->getUser();
        $chambre = $tenant->getChambre();
        $appartement = $chambre ? $chambre->getAppartment() : null;

        $tantieme = 0.0;
        $chores = [];
        $landlord = null;

        if ($chambre && $appartement) {
            $tantieme = $tantiemeCalculator->calculateTantieme($chambre->getSurface(), $appartement->getSuperficieTotale());
            
            // Chasse aux requêtes N+1 : charger les corvées associées à l'appartement avec l'utilisateur assigné
            $chores = $choresRepository->createQueryBuilder('c')
                ->join('c.assignedTo', 'u')
                ->addSelect('u')
                ->andWhere('c.appartment = :app')
                ->setParameter('app', $appartement)
                ->getQuery()
                ->getResult();

            $landlord = $appartement->getLandlord();
        }

        // Récupérer les quittances du locataire
        $quittances = $quittanceRepository->findBy(['tenant' => $tenant], ['transmissionDate' => 'DESC']);

        // Gestion de la messagerie
        $sentMessages = $messageRepository->findBy(['sender' => $tenant], ['dateSent' => 'DESC']);
        $receivedMessages = $messageRepository->findBy(['receiver' => $tenant], ['dateSent' => 'DESC']);

        // Envoyer un message rapide au propriétaire
        if ($request->isMethod('POST') && $request->request->has('send_message') && $landlord) {
            $content = $request->request->get('message_content');

            if (!empty($content)) {
                $message = new Message();
                $message->setSender($tenant);
                $message->setReceiver($landlord);
                $message->setContent($content);
                $message->setDateSent(new \DateTime());

                $entityManager->persist($message);
                $entityManager->flush();

                $this->addFlash('success', 'Votre message a été transmis au propriétaire !');
                return $this->redirectToRoute('app_tenant_dashboard');
            }
        }

        return $this->render('tenant/index.html.twig', [
            'user' => $tenant,
            'chambre' => $chambre,
            'appartement' => $appartement,
            'tantieme' => $tantieme,
            'chores' => $chores,
            'quittances' => $quittances,
            'sentMessages' => $sentMessages,
            'receivedMessages' => $receivedMessages,
            'landlord' => $landlord,
        ]);
    }

    #[Route('/locataire/chore/{id}/complete', name: 'app_tenant_chore_complete')]
    public function completeChore(Chores $chore, EntityManagerInterface $entityManager): Response
    {
        /** @var User $tenant */
        $tenant = $this->getUser();

        if ($chore->getAssignedTo() !== $tenant) {
            throw $this->createAccessDeniedException("Cette corvée ne vous est pas attribuée.");
        }

        $chore->setChoreStatus(ChoresStatus::PAID); // Représente "Fait" dans l'énumération
        $entityManager->flush();

        $this->addFlash('success', 'Félicitations ! Vous avez validé votre tâche ménagère.');
        return $this->redirectToRoute('app_tenant_dashboard');
    }

    #[Route('/locataire/quittance/{id}/download', name: 'app_tenant_quittance_download')]
    public function downloadQuittance(Quittance $quittance): Response
    {
        /** @var User $tenant */
        $tenant = $this->getUser();

        if ($quittance->getTenant() !== $tenant) {
            throw $this->createAccessDeniedException("Accès non autorisé.");
        }

        if ($quittance->getPaymentStatus() !== PaymentStatus::PAID) {
            throw $this->createAccessDeniedException("La quittance n'est téléchargeable que lorsqu'elle est entièrement payée.");
        }

        // Rendu dans un gabarit ultra éco-conçu (utilisant du CSS @media print pour impression propre sans surcoût carbone PDF)
        return $this->render('tenant/quittance_download.html.twig', [
            'quittance' => $quittance,
            'tenant' => $tenant,
            'appartement' => $tenant->getChambre()->getAppartment(),
            'landlord' => $tenant->getChambre()->getAppartment()->getLandlord(),
        ]);
    }
}
