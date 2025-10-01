<?php

namespace App\Controller\Visitor\AboutUs;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AboutUsController extends AbstractController
{
    #[Route('/about-us', name: 'app_visitor_about_us', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('pages/visitor/about-us/index.html.twig', [
        ]);
    }
}
