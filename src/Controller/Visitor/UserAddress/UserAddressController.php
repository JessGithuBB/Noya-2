<?php

namespace App\Controller\Visitor\UserAddress;

use App\Entity\Address;
use App\Entity\User;
use App\Form\UserAddressesTypeForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route; // ou Attribute\Route selon version
use Doctrine\ORM\EntityManagerInterface;

class UserAddressController extends AbstractController
{
    #[Route('/user/address', name: 'app_user_address')]
   public function index(Request $request, EntityManagerInterface $entityManager): Response
{
    /** @var User $user */
    $user = $this->getUser();

    if (!$user) {
        throw $this->createAccessDeniedException('Vous devez être connecté.');
    }

    // Ajouter une adresse vide si aucune n’existe
    if ($user->getAddresses()->isEmpty()) {
        $address = new Address();
        $address->setUser($user);
        $user->addAddress($address);
    }

    // Créer le formulaire lié à l’entité User (avec la collection d'adresses)
    $form = $this->createForm(UserAddressesTypeForm::class, $user);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        $this->addFlash('success', 'Adresses mises à jour avec succès.');

        return $this->redirectToRoute('app_user_address');
    }

    return $this->render('pages/visitor/user_address/index.html.twig', [
        'form' => $form->createView(),
    ]);
}

}