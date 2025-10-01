<?php

namespace App\Controller\Admin;

use App\Entity\Keyword;
use App\Entity\Articles;
use App\Form\ArticlesTypeForm; // ou ArticlesType selon ce que tu utilises
use App\Repository\ArticlesRepository;
use App\Repository\KeywordRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/admin/articles')]
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
    public function create(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger, KeywordRepository $keywordRepository): Response
    {
        $article = new Articles();
        $form = $this->createForm(ArticlesTypeForm::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion du fichier image uploadé
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('articles_images_directory'),
                        $newFilename
                    );
                    $article->setImage($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'image.');
                    // Optionnel : gérer le retour ou autre
                }
            }

            // Slug, dates création et mise à jour
            $slug = $slugger->slug($article->getName())->lower();
            $article->setSlug($slug);
            $article->setCreatedAt(new \DateTimeImmutable());
            $article->setUpdatedAt(new \DateTimeImmutable());

            // Gestion mots-clés depuis le champ texte (non mappé)
            $keywordsText = $form->get('keywordsText')->getData();
            $keywordsArray = array_filter(array_map('trim', explode(',', $keywordsText)));

            foreach ($keywordsArray as $word) {
                if ($word === '') {
                    continue;
                }
                $keyword = $keywordRepository->findOneBy(['name' => $word]);
                if (!$keyword) {
                    $keyword = new Keyword();
                    $keyword->setName($word);
                    $entityManager->persist($keyword);
                }
                if (!$article->getKeywords()->contains($keyword)) {
                    $article->addKeyword($keyword);
                }
            }

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
    public function edit(Articles $article, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger, KeywordRepository $keywordRepository): Response
    {
        $form = $this->createForm(ArticlesTypeForm::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion fichier image
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

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

            // Gestion mots-clés depuis le champ texte
            $keywordsText = $form->get('keywordsText')->getData();
            $keywordsArray = array_filter(array_map('trim', explode(',', $keywordsText)));

            // Nettoyer les mots-clés existants pour remplacement (optionnel)
            foreach ($article->getKeywords() as $existingKeyword) {
                $article->removeKeyword($existingKeyword);
            }

            foreach ($keywordsArray as $word) {
                if ($word === '') {
                    continue;
                }
                $keyword = $keywordRepository->findOneBy(['name' => $word]);
                if (!$keyword) {
                    $keyword = new Keyword();
                    $keyword->setName($word);
                    $entityManager->persist($keyword);
                }
                if (!$article->getKeywords()->contains($keyword)) {
                    $article->addKeyword($keyword);
                }
            }

            $entityManager->flush();

            $this->addFlash('success', 'Article modifié avec succès.');

            return $this->redirectToRoute('app_admin_articles_index');
        }

        return $this->render('pages/admin/articles/edit/index.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_admin_articles_delete', methods: ['POST'])]
    public function delete(Request $request, Articles $article, EntityManagerInterface $entityManager): RedirectResponse
    {
        $submittedToken = $request->request->get('_token');

        if ($this->isCsrfTokenValid('delete-article-' . $article->getId(), $submittedToken)) {
            $entityManager->remove($article);
            $entityManager->flush();

            $this->addFlash('success', 'Article supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Jeton CSRF invalide.');
        }

        return $this->redirectToRoute('app_admin_articles_index');
    }

    #[Route('/mot-cle/{slug}', name: 'app_articles_by_keyword')]
        public function byKeyword(KeywordRepository $keywordRepository, string $slug): Response
        {
            $keyword = $keywordRepository->findOneBy(['slug' => $slug]);

            if (!$keyword) {
                throw $this->createNotFoundException('Mot-clé introuvable');
            }

            return $this->render('pages/articles/by_keyword.html.twig', [
                'keyword' => $keyword,
                'articles' => $keyword->getArticles(),
            ]);
}

}
