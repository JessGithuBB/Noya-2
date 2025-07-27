<?php

namespace App\Controller\User\Profile\EditAddress;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Address; 
use App\Form\AddressTypeForm; 
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
//...

final class EditAddressController extends AbstractController
{
    #[Route('/app_edit_address', name: 'app_edit_address')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        // Exemple : on récupère l'adresse à modifier (en vrai tu adapteras)
        $address = new Address();

        // Création du formulaire lié à l'entité Address
        $form = $this->createForm(AddressTypeForm::class, $address);

        // Traitement du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($address);
            $em->flush();

            $this->addFlash('success', 'Adresse modifiée avec succès.');

            return $this->redirectToRoute('app_edit_address'); // ou autre route
        }

        return $this->render('pages/user/profile/edit_address/index.html.twig', [
            'form' => $form->createView(),   // <== Passe bien la variable `form` à Twig !
        ]);
    }
}

