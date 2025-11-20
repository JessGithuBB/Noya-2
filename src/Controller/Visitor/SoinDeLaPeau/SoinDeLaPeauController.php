<?php

namespace App\Controller\Visitor\SoinDeLaPeau;

use App\Repository\ArticlesRepository;
use App\Service\CatalogFilterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SoinDeLaPeauController extends AbstractController
{
#[Route('/soin-de-la-peau', name: 'app_visitor_soin-de-la-peau', methods: ['GET'])]
public function soin(
    Request $request,
    ArticlesRepository $articlesRepository,
    CatalogFilterService $catalogFilterService
): Response {
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

    return $this->render('pages/visitor/soin-de-la-peau/index.html.twig', [
        'articles' => $articles,
        'filterOptions' => $catalogFilterService->getProductTypeOptions(),
        'issueOptions' => $catalogFilterService->getProblematicOptions(),
        'ratingOptions' => $catalogFilterService->getRatingOptions(),
        'selectedTypes' => $selectedTypes['values'],
        'selectedIssues' => $selectedIssues['values'],
        'selectedRatings' => $selectedRatings['values'],
        'selectedSort' => $sort,
        'filterFormId' => 'catalog-filters-soin',
        'hasActiveFilters' => $catalogFilterService->hasActiveSelection(
            $selectedTypes['values'],
            $selectedIssues['values'],
            $selectedRatings['values'],
            $sort
        ),
]);
}
}
