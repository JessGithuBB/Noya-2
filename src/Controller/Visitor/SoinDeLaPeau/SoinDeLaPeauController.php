<?php

namespace App\Controller\Visitor\SoinDeLaPeau;

use App\Repository\ArticlesRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class SoinDeLaPeauController extends AbstractController
{
#[Route('/soin-de-la-peau', name: 'app_visitor_soin-de-la-peau')]
public function soin(Request $request, CategoryRepository $categoryRepository, ArticlesRepository $articleRepository): Response
{
    $selectedIds = $request->query->all('categories');
    
    $articles = $articleRepository->findByCategories($selectedIds);
    $categories = $categoryRepository->findBy(['parent' => null]);

    return $this->render('pages/visitor/soin-de-la-peau/index.html.twig', [
        'articles' => $articles,
        'categories' => $categories,
        'selectedCategories' => $selectedIds,
    ]);

    $categoriesWithSubs = [];

foreach ($categories as $category) {
    $subcategories = $subcategoryRepository->findBy(['category' => $category]);

    if (count($subcategories) > 0) {
        $categoriesWithSubs[] = [
            'category' => $category,
            'subcategories' => $subcategories,
        ];
    }
}

return $this->render('soin_de_la_peau/index.html.twig', [
    'categories' => $categoriesWithSubs,
    'selectedCategories' => $selectedCategories,
]);

}
}
