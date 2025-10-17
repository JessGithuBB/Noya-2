<?php

namespace App\Service;

use App\Entity\Articles;
use App\Repository\ArticlesRepository;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Service pour gérer le panier d'achat
 * Utilise la session pour stocker les articles
 */
class CartService
{
    private $requestStack;
    private $articlesRepository;

    public function __construct(RequestStack $requestStack, ArticlesRepository $articlesRepository)
    {
        $this->requestStack = $requestStack;
        $this->articlesRepository = $articlesRepository;
    }

    /**
     * Récupère la session de manière paresseuse
     */
    private function getSession()
    {
        return $this->requestStack->getSession();
    }

    /**
     * Ajoute un article au panier
     * @param int $id - ID de l'article
     * @param int $quantity - Quantité à ajouter (par défaut 1)
     */
    public function add(int $id, int $quantity = 1): void
    {
        // Récupérer le panier actuel depuis la session
        $cart = $this->getSession()->get('cart', []);

        // Si l'article existe déjà, on augmente la quantité
        if (!empty($cart[$id])) {
            $cart[$id] += $quantity;
        } else {
            // Sinon on l'ajoute
            $cart[$id] = $quantity;
        }

        // Sauvegarder dans la session
        $this->getSession()->set('cart', $cart);
    }

    /**
     * Supprime un article du panier
     * @param int $id - ID de l'article à supprimer
     */
    public function remove(int $id): void
    {
        $cart = $this->getSession()->get('cart', []);

        if (!empty($cart[$id])) {
            unset($cart[$id]);
        }

        $this->getSession()->set('cart', $cart);
    }

    /**
     * Retourne le contenu complet du panier avec les détails des articles
     * @return array - Tableau avec les articles et leurs quantités
     */
    public function getFullCart(): array
    {
        $cart = $this->getSession()->get('cart', []);
        $cartWithData = [];

        foreach ($cart as $id => $quantity) {
            $article = $this->articlesRepository->find($id);
            
            if ($article) {
                $cartWithData[] = [
                    'article' => $article,
                    'quantity' => $quantity
                ];
            }
        }

        return $cartWithData;
    }

    /**
     * Retourne le total du panier
     * @return float - Montant total
     */
    public function getTotal(): float
    {
        $total = 0;
        $cartWithData = $this->getFullCart();

        foreach ($cartWithData as $item) {
            $total += $item['article']->getSellingPrice() * $item['quantity'];
        }

        return $total;
    }

    /**
     * Retourne le nombre total d'articles dans le panier
     * @return int - Nombre d'articles
     */
    public function getItemCount(): int
    {
        $cart = $this->getSession()->get('cart', []);
        return array_sum($cart);
    }

    /**
     * Vide complètement le panier
     */
    public function clear(): void
    {
        $this->getSession()->remove('cart');
    }

    /**
     * Met à jour la quantité d'un article
     * @param int $id - ID de l'article
     * @param int $quantity - Nouvelle quantité
     */
    public function updateQuantity(int $id, int $quantity): void
    {
        $cart = $this->getSession()->get('cart', []);

        if ($quantity <= 0) {
            $this->remove($id);
        } else {
            $cart[$id] = $quantity;
            $this->getSession()->set('cart', $cart);
        }
    }
}

