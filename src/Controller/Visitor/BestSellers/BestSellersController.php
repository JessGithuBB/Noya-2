<?php

namespace App\Controller\Visitor\BestSellers;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BestSellersController extends AbstractController
{
    #[Route('/best-sellers', name: 'app_visitor_best-sellers',methods:['GET'])]
    public function index(): Response
    {
        return $this->render('pages/visitor/best-sellers/index.html.twig', [
        ]);
    }
}
