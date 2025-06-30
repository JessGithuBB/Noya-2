<?php

namespace App\Controller\Visitor\CorpsEtCheveux;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CorpsEtCheveuxController extends AbstractController
{
    #[Route('/visitor/corps-et-cheveux/', name: 'app_visitor_corps_et_cheveux', methods:['GET'])]
    public function index(): Response
    {
        return $this->render('pages/visitor/corps-et-cheveux/index.html.twig', [
        ]);
    }
}
