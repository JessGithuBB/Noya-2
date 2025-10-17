<?php

namespace App\Controller\Visitor\BestSellers;

use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BestSellersController extends AbstractController
{
    #[Route('/best-sellers', name: 'app_visitor_best-sellers', methods: ['GET'])]
    public function index(ArticlesRepository $articlesRepository): Response
    {
        // Récupérer les articles marqués comme Best Sellers et disponibles
        $articles = $articlesRepository->findBy([
            'is_best_seller' => true,
            'is_available' => true
        ], ['created_at' => 'DESC']);

        return $this->render('pages/visitor/best-sellers/index.html.twig', [
            'articles' => $articles,
        ]);
    }
}
