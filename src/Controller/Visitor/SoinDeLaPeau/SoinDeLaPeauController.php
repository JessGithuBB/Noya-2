<?php

namespace App\Controller\Visitor\SoinDeLaPeau;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SoinDeLaPeauController extends AbstractController
{
    #[Route('/soin-de-la-peau', name: 'app_visitor_soin_de_la_peau', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('pages/visitor/soin-de-la-peau/index.html.twig', [
        ]);
    }
}
