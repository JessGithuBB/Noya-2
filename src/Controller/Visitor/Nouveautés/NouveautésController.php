<?php

namespace App\Controller\Visitor\Nouveautés;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class NouveautésController extends AbstractController
{
    #[Route('/nouveautés', name: 'app_visitor_nouveautés_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('pages/visitor/nouveautés/index.html.twig', [
        ]);
    }
}
