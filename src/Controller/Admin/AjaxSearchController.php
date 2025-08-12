<?php

namespace App\Controller\Admin;

use App\Repository\KeywordRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AjaxSearchController extends AbstractController
{
    #[Route('/ajax/search', name: 'ajax_search')]
    public function search(Request $request, KeywordRepository $keywordRepository): JsonResponse
    {
        $term = $request->query->get('q');

        if (!$term) {
            // Pas de terme, on retourne un tableau vide
            return new JsonResponse(['articles' => []]);
        }

        $keyword = $keywordRepository->findOneBy(['name' => $term]);

        if (!$keyword) {
            return new JsonResponse(['articles' => []]);
        }

        $articles = $keyword->getArticles();
        $results = [];

        foreach ($articles as $article) {
            $results[] = [
                'name' => $article->getName(),
                'image' => $article->getImage(), // adapte si tu utilises VichUploader ou autre
                'price' => $article->getSellingPrice(),
                'url' => $this->generateUrl('article_show', ['id' => $article->getId()]),
            ];
        }

        return new JsonResponse(['articles' => $results]);
    }
}
