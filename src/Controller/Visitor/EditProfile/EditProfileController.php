<?php

namespace App\Controller\Visitor\EditProfile;

use App\Dto\EditProfileDTO;
use App\Form\EditProfileTypeForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class EditProfileController extends AbstractController
{
    #[Route('/edit/profile', name: 'app_edit_profile')]
   public function index(Request $request, EntityManagerInterface $entityManager): Response
{
    /** @var \App\Entity\User $user */
    $user = $this->getUser();
    $entityManager->refresh($user);

    $dto = new EditProfileDTO();
    $dto->firstName = $user->getFirstName();
    $dto->lastName = $user->getLastName();
    $dto->email = $user->getEmail();
    $dto->phoneNumber = $user->getPhoneNumber();

    $form = $this->createForm(EditProfileTypeForm::class, $dto);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $user->setFirstName($dto->firstName);
        $user->setLastName($dto->lastName);
        $user->setEmail($dto->email);
        $user->setPhoneNumber($dto->phoneNumber);

        $entityManager->flush();

        $this->addFlash('success', 'Profil mis à jour avec succès.');
        return $this->redirectToRoute('app_edit_profile');
    }

    return $this->render('pages/visitor/edit_profile/index.html.twig', [
        'form' => $form->createView(),
    ]);
}
}