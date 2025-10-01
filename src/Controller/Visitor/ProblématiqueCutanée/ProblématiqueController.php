<?php

namespace App\Controller\Visitor\ProblématiqueCutanée;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProblématiqueController extends AbstractController
{
    #[Route('/problematique-cutanee', name: 'app_visitor_problematique_cutanee', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('pages/visitor/problematique-cutanee/index.html.twig', [
        ]);
    }
}
