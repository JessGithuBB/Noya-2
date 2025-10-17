<?php

namespace App\Controller\Visitor\Welcome;

use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WelcomeController extends AbstractController
{
    #[Route('/', name: 'app_visitor_welcome', methods: ['GET'])]
    public function index(ArticlesRepository $articlesRepository): Response
    {
        // Récupérer les dernières nouveautés disponibles (limite augmentée à 12)
        $nouveautes = $articlesRepository->findBy([
            'is_new_arrival' => true,
            'is_available' => true
        ], ['created_at' => 'DESC'], 12);

        // Si pas assez de nouveautés, compléter avec les articles disponibles récents
        if (count($nouveautes) < 8) {
            $articlesSupplementaires = $articlesRepository->findBy([
                'is_available' => true
            ], ['created_at' => 'DESC'], 12);
            
            // Fusionner sans doublons
            $nouveautesIds = array_map(fn($a) => $a->getId(), $nouveautes);
            foreach ($articlesSupplementaires as $article) {
                if (!in_array($article->getId(), $nouveautesIds) && count($nouveautes) < 12) {
                    $nouveautes[] = $article;
                }
            }
        }

        return $this->render('pages/visitor/welcome/index.html.twig', [
            'nouveautes' => $nouveautes,
        ]);
    }
}
