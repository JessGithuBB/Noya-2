<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
final class SsCategoryController extends AbstractController
{
    #[Route('category/ss/category', name: 'app_admin_category_ss_category', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('pages/admin/category/ss_category/index.html.twig');
    }
}
