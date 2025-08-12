<?php

namespace App\Controller\User\Profile\AddressController;

use App\Entity\Address;
use App\Form\AddressTypeForm;
use App\Form\UserAddressesTypeForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;   

final class AddressController extends AbstractController
{   
   #[Route('/user/profile/user_address', name: 'app_user_profile_user_address', methods: ['GET', 'POST'])]
    public function addressList(Request $request, EntityManagerInterface $em): Response
    {
        /** @var \App\Entity\User|null $user */
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté.');
        }

        // Liste des adresses de l'utilisateur (optionnel ici, tu peux l'afficher dans Twig via form)
        $addresses = $user->getAddresses();

        // Création du formulaire lié à l'utilisateur, avec la collection d'adresses
        $form = $this->createForm(UserAddressesTypeForm::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Pas besoin de persist user, il est déjà géré par Doctrine si détaché
            $em->flush();

            $this->addFlash('success', 'Adresses mises à jour avec succès.');

            return $this->redirectToRoute('app_user_profile_user_address');
        }

        return $this->render('pages/user/profile/user_address.index.html.twig', [
            'addresses' => $addresses,
            'form' => $form->createView(),
        ]);
    }
}
