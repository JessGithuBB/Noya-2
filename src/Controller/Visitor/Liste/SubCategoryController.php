<?php

namespace App\Controller\Visitor\Liste;

use App\Repository\SsCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class SubCategoryController extends AbstractController
{
    #[Route('/categorie/sous/{slug}', name: 'app_visitor_ss_category_show', methods: ['GET'])]
    public function show(string $slug, SsCategoryRepository $ssCategoryRepository): Response
    {
        $ssCategory = $ssCategoryRepository->findOneBy(['slug' => $slug]);

        if (!$ssCategory) {
            throw $this->createNotFoundException('Sous-catégorie introuvable');
        }

        return $this->render('pages/visitor/liste/ss-category/show.html.twig', [
            'ss_category' => $ssCategory,
        ]);
    }
}


