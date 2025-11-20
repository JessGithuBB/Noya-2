<?php

namespace App\Controller\Visitor\Article;

use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ArticleController extends AbstractController
{
    #[Route('/article/{slug}', name: 'app_visitor_article_show', methods: ['GET'])]
    public function show(string $slug, ArticlesRepository $articlesRepository): Response
    {
        $article = $articlesRepository->findOneBy(['slug' => $slug]);

        if (!$article) {
            throw $this->createNotFoundException('Article introuvable');
        }

        // Récupérer les images supplémentaires pour le carrousel
        $carouselImages = [];
        foreach ($article->getImages() as $articleImage) {
            if ($articleImage->getImageName()) {
                $carouselImages[] = $articleImage->getImageName();
            }
        }
        
        // Limiter à 3 images pour le carrousel
        $carouselImages = array_slice($carouselImages, 0, 3);
        $mainImage = $article->getImage();

        // Données mockées pour les avis (à remplacer par une vraie entité Review plus tard)
        // Ces avis simulent ceux de l'image fournie par l'utilisateur
        $reviews = [
            [
                'id' => 1,
                'author' => 'Lola',
                'date' => new \DateTime('2025-05-03'),
                'rating' => 5,
                'title' => 'Une crème incroyable!',
                'comment' => 'Je l\'adore ! La texture est fine et facile à appliquer. Le résultat du hâle est modulable en fonction des souhaits'
            ],
            [
                'id' => 2,
                'author' => 'Claire',
                'date' => new \DateTime('2025-04-17'),
                'rating' => 3,
                'title' => 'Plutôt mitigée',
                'comment' => 'Certes le produit sent bon et et agréable à utiliser mais je ne vois pas vraiment le côté bronzage'
            ],
            // Ajout d'autres avis pour tester la pagination
            [
                'id' => 3,
                'author' => 'Sophie',
                'date' => new \DateTime('2025-03-15'),
                'rating' => 5,
                'title' => 'Parfait!',
                'comment' => 'Excellent produit, je le recommande vivement. Qualité au rendez-vous.'
            ],
            [
                'id' => 4,
                'author' => 'Marie',
                'date' => new \DateTime('2025-03-10'),
                'rating' => 4,
                'title' => 'Très bon produit',
                'comment' => 'Très satisfaite de mon achat, le produit répond à mes attentes.'
            ],
            [
                'id' => 5,
                'author' => 'Emma',
                'date' => new \DateTime('2025-02-28'),
                'rating' => 5,
                'title' => 'Formidable!',
                'comment' => 'Un produit de qualité supérieure, je suis très contente.'
            ],
        ];

        // Calculer les statistiques d'avis
        $totalReviews = count($reviews);
        $averageRating = $totalReviews > 0 ? array_sum(array_column($reviews, 'rating')) / $totalReviews : 0;
        $ratingDistribution = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0, 0 => 0];
        foreach ($reviews as $review) {
            $ratingDistribution[$review['rating']]++;
        }

        return $this->render('pages/visitor/article/show.html.twig', [
            'article' => $article,
            'carouselImages' => $carouselImages,
            'mainImage' => $mainImage,
            'reviews' => $reviews,
            'totalReviews' => $totalReviews,
            'averageRating' => $averageRating,
            'ratingDistribution' => $ratingDistribution,
        ]);
    }
}

