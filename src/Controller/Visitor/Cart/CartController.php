<?php

namespace App\Controller\Visitor\Cart;

use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/panier')]
final class CartController extends AbstractController
{
    /**
     * Affiche le contenu du panier
     */
    #[Route('/', name: 'app_cart_index', methods: ['GET'])]
    public function index(CartService $cartService): Response
    {
        return $this->render('pages/visitor/cart/index.html.twig', [
            'items' => $cartService->getFullCart(),
            'total' => $cartService->getTotal(),
        ]);
    }

    /**
     * Ajoute un article au panier
     */
    #[Route('/add/{id}', name: 'app_cart_add', methods: ['POST', 'GET'])]
    public function add(int $id, CartService $cartService, Request $request): Response
    {
        $quantity = (int) $request->query->get('quantity', 1);
        
        $cartService->add($id, $quantity);

        $this->addFlash('success', 'Article ajouté au panier avec succès !');

        // Rediriger vers la page précédente ou vers le panier
        $referer = $request->headers->get('referer');
        if ($referer) {
            return $this->redirect($referer);
        }

        return $this->redirectToRoute('app_cart_index');
    }

    /**
     * Supprime un article du panier
     */
    #[Route('/remove/{id}', name: 'app_cart_remove', methods: ['GET'])]
    public function remove(int $id, CartService $cartService): Response
    {
        $cartService->remove($id);

        $this->addFlash('info', 'Article retiré du panier.');

        return $this->redirectToRoute('app_cart_index');
    }

    /**
     * Met à jour la quantité d'un article
     */
    #[Route('/update/{id}', name: 'app_cart_update', methods: ['POST'])]
    public function update(int $id, Request $request, CartService $cartService): Response
    {
        $quantity = (int) $request->request->get('quantity', 1);
        
        $cartService->updateQuantity($id, $quantity);

        $this->addFlash('success', 'Quantité mise à jour.');

        return $this->redirectToRoute('app_cart_index');
    }

    /**
     * Vide complètement le panier
     */
    #[Route('/clear', name: 'app_cart_clear', methods: ['GET'])]
    public function clear(CartService $cartService): Response
    {
        $cartService->clear();

        $this->addFlash('info', 'Panier vidé.');

        return $this->redirectToRoute('app_cart_index');
    }
}


