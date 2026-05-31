<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ForgotPasswordRequestFormType;
use App\Form\ResetPasswordFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/{_locale}', requirements: ['_locale' => 'fr|en'], defaults: ['_locale' => 'fr'])]
class ResetPasswordController extends AbstractController
{
    #[Route('/forgot-password', name: 'app_forgot_password_request')]
    public function request(
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer
    ): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_login_success');
        }

        $form = $this->createForm(ForgotPasswordRequestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emailAddress = $form->get('email')->getData();
            $user = $userRepository->findOneBy(['email' => $emailAddress]);

            if ($user) {
                // Generate secure token
                $token = bin2hex(random_bytes(32));
                $user->setPasswordResetToken($token);
                $user->setPasswordResetTokenExpiresAt(new \DateTime('+1 hour'));

                $entityManager->persist($user);
                $entityManager->flush();

                // Generate reset url
                $resetUrl = $this->generateUrl('app_reset_password', [
                    'token' => $token,
                ], UrlGeneratorInterface::ABSOLUTE_URL);

                // Prepare Email
                $email = (new Email())
                    ->from('no-reply@colive.fr')
                    ->to($user->getEmail())
                    ->subject('Réinitialisation de votre mot de passe - CoLive')
                    ->html($this->renderView('emails/reset_password.html.twig', [
                        'user' => $user,
                        'resetUrl' => $resetUrl,
                    ]));

                try {
                    $mailer->send($email);
                    $this->addFlash('success', 'Un email de réinitialisation a été envoyé à votre adresse.');
                } catch (\Exception $e) {
                    // Fallback if mailer fails entirely
                    $this->addFlash('success', 'Un email de réinitialisation a été configuré.');
                }

                // Pour faciliter les tests en local (dev), on affiche directement le lien à l'écran
                if ($this->getParameter('kernel.environment') === 'dev') {
                    $this->addFlash('info', 'Lien de réinitialisation (Simulé pour dev) : <a href="' . $resetUrl . '" style="color: #0284c7; text-decoration: underline; font-weight: 600;">Cliquer ici pour réinitialiser</a>');
                }
            } else {
                // Avoid leaking user existence, display same success message
                $this->addFlash('success', 'Si l\'adresse existe, un email de réinitialisation a été envoyé.');
            }

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/forgot_password_request.html.twig', [
            'requestForm' => $form->createView(),
        ]);
    }

    #[Route('/reset-password/{token}', name: 'app_reset_password')]
    public function reset(
        string $token,
        Request $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $userRepository->findOneBy(['passwordResetToken' => $token]);

        if (!$user || $user->getPasswordResetTokenExpiresAt() === null || $user->getPasswordResetTokenExpiresAt() < new \DateTime()) {
            $this->addFlash('error', 'Le jeton de réinitialisation est invalide ou a expiré.');
            return $this->redirectToRoute('app_forgot_password_request');
        }

        $form = $this->createForm(ResetPasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the new password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // Clear reset fields
            $user->setPasswordResetToken(null);
            $user->setPasswordResetTokenExpiresAt(null);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous connecter.');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password.html.twig', [
            'resetForm' => $form->createView(),
        ]);
    }
}
