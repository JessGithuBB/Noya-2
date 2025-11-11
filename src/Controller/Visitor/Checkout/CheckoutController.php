<?php

namespace App\Controller\Visitor\Checkout;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/checkout')]
final class CheckoutController extends AbstractController
{
    /**
     * Page de checkout - récapitulatif de la commande
     */
    #[Route('/', name: 'app_checkout_index', methods: ['GET'])]
    public function index(CartService $cartService): Response
    {
        // Vérifier que l'utilisateur est connecté
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Vérifier que le panier n'est pas vide
        $items = $cartService->getFullCart();
        if (empty($items)) {
            $this->addFlash('warning', 'Votre panier est vide.');
            return $this->redirectToRoute('app_cart_index');
        }

        $total = $cartService->getTotal();

        return $this->render('pages/visitor/checkout/index.html.twig', [
            'items' => $items,
            'total' => $total,
        ]);
    }

    /**
     * Page de sélection du mode de paiement
     */
    #[Route('/payment', name: 'app_checkout_payment', methods: ['GET', 'POST'])]
    public function payment(Request $request, CartService $cartService): Response
    {
        // Vérifier que l'utilisateur est connecté
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Vérifier que le panier n'est pas vide
        $items = $cartService->getFullCart();
        if (empty($items)) {
            $this->addFlash('warning', 'Votre panier est vide.');
            return $this->redirectToRoute('app_cart_index');
        }

        $total = $cartService->getTotal();

        // Si le formulaire est soumis
        if ($request->isMethod('POST')) {
            $paymentMethod = $request->request->get('payment_method');
            
            if (!$paymentMethod) {
                $this->addFlash('error', 'Veuillez sélectionner une méthode de paiement.');
                return $this->redirectToRoute('app_checkout_payment');
            }

            // Rediriger vers la page de traitement du paiement
            return $this->redirectToRoute('app_checkout_process', [
                'method' => $paymentMethod
            ]);
        }

        return $this->render('pages/visitor/checkout/payment.html.twig', [
            'items' => $items,
            'total' => $total,
        ]);
    }

    /**
     * Traitement du paiement
     */
    #[Route('/process/{method}', name: 'app_checkout_process', methods: ['GET', 'POST'])]
    public function process(
        string $method,
        Request $request,
        CartService $cartService,
        EntityManagerInterface $entityManager
    ): Response {
        // Vérifier que l'utilisateur est connecté
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Vérifier que le panier n'est pas vide
        $items = $cartService->getFullCart();
        if (empty($items)) {
            $this->addFlash('warning', 'Votre panier est vide.');
            return $this->redirectToRoute('app_cart_index');
        }

        $total = $cartService->getTotal();
        $user = $this->getUser();

        // Méthodes de paiement valides
        $validMethods = ['visa', 'mastercard', 'paypal', 'apple_pay', 'google_pay', 'tether', 'ethereum'];
        
        if (!in_array($method, $validMethods)) {
            $this->addFlash('error', 'Méthode de paiement invalide.');
            return $this->redirectToRoute('app_checkout_payment');
        }

        // Si le formulaire est soumis (confirmation du paiement)
        if ($request->isMethod('POST')) {
            // Créer la commande
            $order = new Order();
            $order->setUser($user);
            $order->setTotalAmount((string)$total);
            $order->setPaymentMethod($method);
            $order->setStatus('processing');
            $order->setPaymentStatus('pending');

            // Ajouter les articles à la commande
            foreach ($items as $item) {
                $orderItem = new OrderItem();
                $orderItem->setArticle($item['article']);
                $orderItem->setQuantity($item['quantity']);
                $orderItem->setUnitPrice((string)$item['article']->getSellingPrice());
                $orderItem->setTotalPrice((string)($item['article']->getSellingPrice() * $item['quantity']));
                $order->addOrderItem($orderItem);
            }

            // Sauvegarder la commande
            $entityManager->persist($order);
            $entityManager->flush();

            // Simuler le traitement du paiement
            // Dans un vrai système, vous intégreriez les API de paiement ici
            $order->setPaymentStatus('paid');
            $order->setPaidAt(new \DateTimeImmutable());
            $order->setStatus('completed');
            
            // Générer un ID de transaction fictif
            $order->setPaymentTransactionId('TXN-' . strtoupper(uniqid()));
            
            $entityManager->flush();

            // Vider le panier
            $cartService->clear();

            // Rediriger vers la page de confirmation
            return $this->redirectToRoute('app_checkout_success', [
                'orderNumber' => $order->getOrderNumber()
            ]);
        }

        return $this->render('pages/visitor/checkout/process.html.twig', [
            'method' => $method,
            'items' => $items,
            'total' => $total,
        ]);
    }

    /**
     * Page de confirmation de commande
     */
    #[Route('/success/{orderNumber}', name: 'app_checkout_success', methods: ['GET'])]
    public function success(string $orderNumber, EntityManagerInterface $entityManager): Response
    {
        // Vérifier que l'utilisateur est connecté
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $order = $entityManager->getRepository(Order::class)->findOneByOrderNumber($orderNumber);

        if (!$order) {
            $this->addFlash('error', 'Commande introuvable.');
            return $this->redirectToRoute('app_visitor_welcome');
        }

        // Vérifier que la commande appartient bien à l'utilisateur
        if ($order->getUser() !== $this->getUser()) {
            $this->addFlash('error', 'Accès refusé.');
            return $this->redirectToRoute('app_visitor_welcome');
        }

        return $this->render('pages/visitor/checkout/success.html.twig', [
            'order' => $order,
        ]);
    }
}


