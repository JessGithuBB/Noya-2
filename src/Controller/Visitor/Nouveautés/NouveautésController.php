<?php

namespace App\Controller\Visitor\Nouveautés;

use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class NouveautésController extends AbstractController
{
    #[Route('/nouveautés', name: 'app_visitor_nouveautés_index', methods: ['GET'])]
    public function index(ArticlesRepository $articlesRepository): Response
    {
        // Récupérer les articles marqués comme nouveautés et disponibles
        $articles = $articlesRepository->findBy([
            'is_new_arrival' => true,
            'is_available' => true
        ], ['created_at' => 'DESC']);

        return $this->render('pages/visitor/nouveautés/index.html.twig', [
            'articles' => $articles,
        ]);
    }
}
