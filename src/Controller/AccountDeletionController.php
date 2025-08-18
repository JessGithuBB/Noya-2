<?php

namespace App\Controller;

use App\Entity\AccountDeletionRequest;
use App\Repository\AccountDeletionRequestRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AccountDeletionController extends AbstractController
{
    #[Route('/account/delete/request', name: 'account_delete_request', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function requestDeletion(Request $request, EntityManagerInterface $em, MailerInterface $mailer): Response
    {
        $user = $this->getUser();
        $token = bin2hex(random_bytes(32));
        $scheduledAt = (new \DateTime())->modify('+1 month');

        $deletionRequest = new AccountDeletionRequest();
        $deletionRequest->setUser($user);
        $deletionRequest->setToken($token);
        $deletionRequest->setScheduledAt($scheduledAt);

        $em->persist($deletionRequest);
        $user->setPendingDeletionAt($scheduledAt);
        $em->flush();

        $mail = (new Email())
            ->from('no-reply@monsite.local')
            ->to($user->getEmail())
            ->subject('Confirmation suppression de compte')
            ->html($this->renderView('emails/account_delete_confirm.html.twig', [
                'token' => $token,
                'scheduledAt' => $scheduledAt
            ]));

        $mailer->send($mail);

        return $this->json(['message' => 'Demande enregistrée, vérifiez vos emails.']);
    }

    #[Route('/account/delete/confirm/{token}', name: 'account_delete_confirm', methods: ['GET'])]
    public function confirm($token, AccountDeletionRequestRepository $repo): Response
    {
        $request = $repo->findOneBy(['token' => $token]);
        if (!$request || $request->getCancelledAt()) {
            throw $this->createNotFoundException();
        }

        return $this->render('account/delete_confirm.html.twig', [
            'scheduledAt' => $request->getScheduledAt()
        ]);
    }

    #[Route('/account/delete/cancel', name: 'account_delete_cancel', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function cancelDeletion(EntityManagerInterface $em, AccountDeletionRequestRepository $repo): Response
    {
        $user = $this->getUser();
        $req = $repo->findOneBy(['user' => $user, 'cancelledAt' => null]);
        if ($req) {
            $req->setCancelledAt(new \DateTime());
            $user->setPendingDeletionAt(null);
            $em->flush();
        }
        return $this->json(['message' => 'Suppression annulée.']);
    }
}
