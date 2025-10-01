<?php

namespace App\Controller\User\Profile\EditProfile;

use App\Dto\EditProfileDTO;
use App\Form\EditProfileTypeForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

final class EditProfileController extends AbstractController
{
    #[Route('/edit/profile', name: 'app_edit_profile')]
    public function index(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
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
            /** @var EditProfileDTO $dto */
            $dto = $form->getData();

            $user->setFirstName($dto->firstName);
            $user->setLastName($dto->lastName);
            $user->setEmail($dto->email);
            $user->setPhoneNumber($dto->phoneNumber);

            // Gestion de l'upload d'avatar
            $avatarFile = $form->get('avatar')->getData();
            if ($avatarFile) {
                $originalFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$avatarFile->guessExtension();

                try {
                    $avatarFile->move(
                        $this->getParameter('avatars_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Erreur lors de l\'upload de la photo.');
                    return $this->redirectToRoute('app_edit_profile');
                }

                // Optionnel : supprimer l'ancienne photo si besoin
                if ($user->getAvatar()) {
                    $oldAvatarPath = $this->getParameter('avatars_directory').'/'.$user->getAvatar();
                    if (file_exists($oldAvatarPath)) {
                        @unlink($oldAvatarPath);
                    }
                }

                $user->setAvatar($newFilename);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Profil mis à jour avec succès.');

            return $this->redirectToRoute('app_edit_profile');
        }

        return $this->render('pages/user/profile/edit_profile/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
