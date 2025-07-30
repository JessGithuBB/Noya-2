<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
final class CategoryController extends AbstractController
{
    #[Route('/category/index', name: 'app_admin_category', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('pages/admin/category/index.html.twig');
    }
}
