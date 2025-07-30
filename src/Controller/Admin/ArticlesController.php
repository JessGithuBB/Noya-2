<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
final class ArticlesController extends AbstractController
{
    #[Route('/articles', name: 'app_admin_articles',methods:['GET'])]
    public function index(): Response
    {
        return $this->render('pages/admin/articles/index.html.twig');
    }
}
