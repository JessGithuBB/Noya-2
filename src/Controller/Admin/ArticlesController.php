<?php

namespace App\Controller\Admin;

use App\Entity\Articles;
use App\Form\ArticlesTypeForm; // ou ArticlesType selon ce que tu utilises
use App\Repository\ArticlesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response; // Correction ici, c'est Annotation, pas Attribute
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/articles')] // Préfixe commun pour toutes les routes ici
final class ArticlesController extends AbstractController
{
    #[Route('/index', name: 'app_admin_articles_index', methods: ['GET'])]
    public function index(ArticlesRepository $articlesRepository): Response
    {
        $articles = $articlesRepository->findAll();

        return $this->render('pages/admin/articles/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/create', name: 'app_admin_articles_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $article = new Articles();
        $form = $this->createForm(ArticlesTypeForm::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('articles_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'image.');
                    // Optionnel : return ou autre gestion
                }

                $article->setImage($newFilename);
            }

            $slug = $slugger->slug($article->getName())->lower();
            $article->setSlug($slug);
            $article->setCreatedAt(new \DateTimeImmutable());
            $article->setUpdatedAt(new \DateTimeImmutable());

            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success', "L'article a été créé avec succès.");

            return $this->redirectToRoute('app_admin_articles_index');
        }

        return $this->render('pages/admin/articles/create/index.html.twig', [
            'form' => $form->createView(),
            'article' => $article,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_admin_articles_edit', methods: ['GET', 'POST'])]
    public function edit(Articles $article, Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ArticlesTypeForm::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('articles_images_directory'),
                        $newFilename
                    );
                    $article->setImage($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'image.');
                }
            }

            $article->setSlug($slugger->slug($article->getName())->lower());
            $article->setUpdatedAt(new \DateTimeImmutable());

            $em->flush();

            $this->addFlash('success', 'Article modifié avec succès.');

            return $this->redirectToRoute('app_admin_articles_index');
        }

        return $this->render('pages/admin/articles/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_admin_articles_delete', methods: ['POST'])]
    public function delete(Request $request, Articles $article, EntityManagerInterface $em): RedirectResponse
    {
        $submittedToken = $request->request->get('_token');

        if ($this->isCsrfTokenValid('delete-article-'.$article->getId(), $submittedToken)) {
            $em->remove($article);
            $em->flush();

            $this->addFlash('success', 'Article supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Jeton CSRF invalide.');
        }

        return $this->redirectToRoute('app_admin_articles_index');
    }
}
