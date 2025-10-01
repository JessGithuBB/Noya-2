<?php

namespace App\Controller\Admin;

use App\Entity\SsCategory;
use App\Form\SsCategoryForm;
use App\Repository\SsCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin')]
class SsCategoryController extends AbstractController
{
    private SsCategoryRepository $ssCategoryRepository;

    public function __construct(SsCategoryRepository $ssCategoryRepository)
    {
        $this->ssCategoryRepository = $ssCategoryRepository;
    }

    #[Route('/', name: 'app_admin_category_ss_category', methods: ['GET'])]
    public function index(): Response
    {
        $ss_categories = $this->ssCategoryRepository->findAll();

        return $this->render('pages/admin/category/ss-category/index.html.twig', [
            'ss_categories' => $ss_categories,
        ]);
    }

    #[Route('/create', name: 'app_admin_category_ss_category_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $ssCategory = new SsCategory();
        $form = $this->createForm(SsCategoryForm::class, $ssCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $existing = $this->ssCategoryRepository->findOneBy(['name' => $ssCategory->getName()]);
            if ($existing) {
                $this->addFlash('error', 'Cette sous-catégorie existe déjà.');

                return $this->redirectToRoute('app_admin_category_ss_category_create');
            }

            $slug = $slugger->slug($ssCategory->getName())->lower();
            $ssCategory->setSlug($slug);
            $ssCategory->setCreatedAt(new \DateTimeImmutable());
            $ssCategory->setUpdatedAt(new \DateTimeImmutable());

            $em->persist($ssCategory);
            $em->flush();

            $this->addFlash('success', 'La sous-catégorie a été créée.');

            return $this->redirectToRoute('app_admin_category_ss_category');
        }

        return $this->render('pages/admin/category/ss-category/create/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id<\d+>}', name: 'app_admin_category_ss_category_edit', methods: ['GET', 'POST'])]
    public function edit(SsCategory $ssCategory, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SsCategoryForm::class, $ssCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ssCategory->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->flush();

            $this->addFlash('success', 'La sous-catégorie a été modifiée.');

            return $this->redirectToRoute('app_admin_category_ss_category');
        }

        return $this->render('pages/admin/category/edit/index.html.twig', [
            'form' => $form->createView(),
            'category' => $ssCategory,
        ]);
    }

    #[Route('/delete/{id<\d+>}', name: 'app_admin_category_ss_category_delete', methods: ['POST'])]
    public function delete(SsCategory $ssCategory, EntityManagerInterface $entityManager, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete-ss_category-'.$ssCategory->getId(), $request->request->get('_token'))) {
            $categoryName = $ssCategory->getName();
            $entityManager->remove($ssCategory);
            $entityManager->flush();

            $this->addFlash('info', "La sous-catégorie $categoryName a été supprimée.");
        }

        return $this->redirectToRoute('app_admin_category_ss_category');
    }
}
