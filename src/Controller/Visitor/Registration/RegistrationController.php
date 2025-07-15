<?php

namespace App\Controller\Visitor\Registration;

use App\Entity\User;
use App\Form\RegistrationForm;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier)
    {
    }

    #[Route('/inscription', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('password')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
            $user->setRoles(['ROLE_USER']);
            $user->setCreatedAt(new \DateTimeImmutable());

            $entityManager->persist($user);
            $entityManager->flush();
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())

                ->from(new Address('contact.noyafr@gmail.com', 'Noya'))
                ->to((string) $user->getEmail())
                ->subject('Verification de votre adresse e-mail')
                ->htmlTemplate('emails/confirmation_email.html.twig')
            );

            $this->addFlash('confirmation_email_sent', 'Merci de valider votre compte en cliquant sur le lien envoyé par email.');

            // Juste après la confirmation et avant la redirection
            return $this->redirectToRoute('app_visitor_welcome');
        }

        return $this->render('pages/visitor/registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

        #[Route('/verify/email', name: 'app_verify_email')]
        public function verifyUserEmail(Request $request, TranslatorInterface $translator, UserRepository $userRepository): Response
        {
            $id = $request->query->get('id');

            if (null === $id) {
                return $this->redirectToRoute('app_register');
            }

            $user = $userRepository->find($id);

            if (null === $user) {
                return $this->redirectToRoute('app_register');
            }

            try {
                $this->emailVerifier->handleEmailConfirmation($request, $user);
                $this->addFlash('success', $translator->trans('Votre adresse email a bien été vérifiée.', [], 'VerifyEmailBundle'));
            } catch (VerifyEmailExceptionInterface $exception) {
                $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));
                return $this->redirectToRoute('app_register');
            }
            //$this->addFlash('confirmation_email_sent', 'Merci de valider votre compte en cliquant sur le lien envoyé par email.');//

            return $this->redirectToRoute('app_visitor_welcome'); // Modifier cette route selon ta logique
        }

}