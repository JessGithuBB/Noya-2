<?php

namespace App\Controller\Visitor\CorpsEtCheveux;

use App\Repository\ArticlesRepository;
use App\Repository\KeywordRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CorpsEtCheveuxController extends AbstractController
{
    #[Route('/visitor/corps-et-cheveux/', name: 'app_visitor_corps_et_cheveux', methods: ['GET'])]
    public function index(Request $request, KeywordRepository $keywordRepository, ArticlesRepository $articlesRepository): Response
    {
        // Récupérer tous les keywords
        $keywords = $keywordRepository->findAll();
        
        // Récupérer le keyword sélectionné
        $selectedKeywordId = $request->query->get('keyword');
        
        // Filtrer les articles selon le keyword sélectionné
        if ($selectedKeywordId) {
            $selectedKeyword = $keywordRepository->find($selectedKeywordId);
            $articles = $selectedKeyword ? $selectedKeyword->getArticles()->toArray() : [];
            // Filtrer uniquement les articles disponibles
            $articles = array_filter($articles, fn($article) => $article->isAvailable());
        } else {
            // Afficher tous les articles disponibles
            $articles = $articlesRepository->findBy(['is_available' => true], ['created_at' => 'DESC']);
        }

        return $this->render('pages/visitor/corps-et-cheveux/index.html.twig', [
            'keywords' => $keywords,
            'articles' => $articles,
        ]);
    }
}
