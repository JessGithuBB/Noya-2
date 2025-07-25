<?php

namespace App\Controller\Visitor\ProfileUser;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function show(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        return $this->render('pages/visitor/profile/index.html.twig', [
            'user' => $user,
            'addresses' => $user->getAddresses(), // c’est une collection, pas un seul objet
        ]);
    }
}