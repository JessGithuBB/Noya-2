<?php

namespace App\Controller\Visitor\ProfileUser;

use App\Entity\User;
use App\Form\EditProfileTypeForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function edit(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        // Vérifier que l'utilisateur est bien connecté
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        /** @var User $user */
        $user = $this->getUser();

        // Crée le formulaire lié à l'utilisateur actuel
        $form = $this->createForm(EditProfileTypeForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarde les modifications
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre profil a bien été mis à jour.');

            return $this->redirectToRoute('app_profile'); // redirige vers la page de profil
        }

        return $this->render('pages/visitor/edit_profile/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
