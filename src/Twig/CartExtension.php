<?php

namespace App\Twig;

use App\Service\CartService;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

/**
 * Extension Twig pour rendre le panier accessible dans tous les templates
 */
class CartExtension extends AbstractExtension implements GlobalsInterface
{
    private $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Rend les données du panier disponibles globalement
     * Utilisable dans les templates avec {{ cart_count }}
     */
    public function getGlobals(): array
    {
        return [
            'cart_count' => $this->cartService->getItemCount(),
        ];
    }
}


