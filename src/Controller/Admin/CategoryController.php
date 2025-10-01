<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\AdminCategoryFormType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin')]
final class CategoryController extends AbstractController
{
    #[Route('/category/index', name: 'app_admin_category', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('pages/admin/category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/category/create', name: 'app_admin_category_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $category = new Category();
        $form = $this->createForm(AdminCategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($category->getName())->lower();
            $category->setSlug($slug);
            // Si tes champs sont nullable, tu peux ne rien setter
            $category->setCreatedAt(new \DateTimeImmutable());
            $category->setUpdatedAt(new \DateTimeImmutable());

            $em->persist($category);
            $em->flush();

            $this->addFlash('success', 'La catégorie a été créée.');

            return $this->redirectToRoute('app_admin_category');
        }

        return $this->render('pages/admin/category/create/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/category/edit/{id<\d+>}', name: 'app_admin_category_edit', methods: ['GET', 'POST'])]
    public function edit(Category $category, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdminCategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', 'La catégorie a été modifiée');

            return $this->redirectToRoute('app_admin_category');
        }

        return $this->render('pages/admin/category/edit/index.html.twig', [
            'form' => $form->createView(),
            'category' => $category,
        ]);
    }

    #[Route('/category/delete/{id<\d+>}', name: 'app_admin_category_delete', methods: ['POST'])]
    public function delete(Category $category, EntityManagerInterface $entityManager, Request $request): Response
    {
        // Vérification du token CSRF (bonne pratique pour éviter les suppressions accidentelles)
        if ($this->isCsrfTokenValid('delete-category-'.$category->getId(), $request->request->get('_token'))) {
            $categoryName = $category->getName();
            $entityManager->remove($category);
            $entityManager->flush();

            $this->addFlash('info', "La catégorie $categoryName a été supprimée avec succès.");
        }

        return $this->redirectToRoute('app_admin_category');

        
    }
}
