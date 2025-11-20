<?php

namespace App\Controller\Visitor\CorpsEtCheveux;

use App\Repository\ArticlesRepository;
use App\Service\CatalogFilterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CorpsEtCheveuxController extends AbstractController
{
    #[Route('/visitor/corps-et-cheveux/', name: 'app_visitor_corps_et_cheveux', methods: ['GET'])]
    public function index(
        Request $request,
        ArticlesRepository $articlesRepository,
        CatalogFilterService $catalogFilterService
    ): Response
    {
        $selectedTypes = $catalogFilterService->resolveProductTypes($request->query->all('types'));
        $selectedIssues = $catalogFilterService->resolveProblematicKeywords($request->query->all('issues'));
        $selectedRatings = $catalogFilterService->resolveRatingFilters($request->query->all('ratings'));

        $sort = $catalogFilterService->sanitizeSort($request->query->get('sort'));

        $articles = $articlesRepository->findWithFilters(
            $selectedTypes['idList'],
            $selectedIssues['idList'],
            array_map('mb_strtolower', $selectedRatings['keywordNames']),
            $sort,
            $selectedRatings['scoreMap'],
            $sort === CatalogFilterService::SORT_BEST_SELLERS
        );

        return $this->render('pages/visitor/corps-et-cheveux/index.html.twig', [
            'articles' => $articles,
            'filterOptions' => $catalogFilterService->getProductTypeOptions(),
            'issueOptions' => $catalogFilterService->getProblematicOptions(),
            'ratingOptions' => $catalogFilterService->getRatingOptions(),
            'selectedTypes' => $selectedTypes['values'],
            'selectedIssues' => $selectedIssues['values'],
            'selectedRatings' => $selectedRatings['values'],
            'selectedSort' => $sort,
            'filterFormId' => 'catalog-filters-corps',
            'hasActiveFilters' => $catalogFilterService->hasActiveSelection(
                $selectedTypes['values'],
                $selectedIssues['values'],
                $selectedRatings['values'],
                $sort
            ),
        ]);
    }
}
