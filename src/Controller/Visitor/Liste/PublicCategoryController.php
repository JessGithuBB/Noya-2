<?php

// src/Controller/CategoryController.php

namespace App\Controller\Visitor\Liste;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublicCategoryController extends AbstractController
{
    #[Route('/visitor/liste', name: 'app_visitor_liste', methods: ['GET'])]
    public function list(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('pages/visitor/liste/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/visitor/{slug}', name: 'app_liste_ss_category', methods: ['GET'])]
    public function show(string $slug, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findOneBy(['slug' => $slug]);

        if (!$category) {
            throw $this->createNotFoundException('Catégorie non trouvée');
        }

        return $this->render('pages/visitor/liste/ss-category/index.html.twig', [
            'category' => $category,
            'subCategories' => $category->getSubCategories(),
        ]);
    }
}
