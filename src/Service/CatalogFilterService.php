<?php

namespace App\Service;

use App\Repository\KeywordRepository;
use App\Repository\SsCategoryRepository;

/**
 * Regroupe la logique de préparation et de normalisation
 * des filtres/tri utilisés sur les pages catalogue visiteurs.
 */
final class CatalogFilterService
{
    public const SORT_BEST_SELLERS = 'best_sellers';
    public const SORT_PRICE_ASC = 'price_asc';
    public const SORT_PRICE_DESC = 'price_desc';
    public const SORT_RATING_DESC = 'rating_desc';

    private const PRODUCT_TYPES = [
        ['label' => 'Sérums', 'value' => 'serum'],
        ['label' => 'Soins solaire', 'value' => 'soins-solaires'],
        ['label' => 'Crème hydratante', 'value' => 'creme-hydratante'],
        ['label' => 'Exfoliant', 'value' => 'exfoliant'],
        ['label' => 'Sérum contour des yeux', 'value' => 'serum-contour-yeux'],
        ['label' => 'Nettoyant pour le visage', 'value' => 'nettoyant-pour-le-visage'],
        ['label' => 'Huile de visage', 'value' => 'huile-pour-le-visage'],
        ['label' => 'Tonique', 'value' => 'tonique'],
    ];

    private const PROBLEMATIC_OPTIONS = [
        ['label' => 'Sécheresse', 'value' => 'secheresse', 'keyword_name' => 'Sécheresse'],
        ['label' => 'Cheveux', 'value' => 'cheveux', 'keyword_name' => 'Cheveux'],
        ['label' => 'Cernes', 'value' => 'cernes', 'keyword_name' => 'Cernes'],
        ['label' => 'Déshydratation', 'value' => 'deshydratation', 'keyword_name' => 'Déshydratation'],
        ['label' => 'Irrégularité texture', 'value' => 'irregularite-texture', 'keyword_name' => 'Irrégularité texture'],
        ['label' => 'Nettoyage', 'value' => 'nettoyage', 'keyword_name' => 'Nettoyage'],
        ['label' => 'Signes de l’âge', 'value' => 'signes-age', 'keyword_name' => 'Signes de l\'âge'],
        ['label' => 'Hyperpigmentation', 'value' => 'hyperpigmentation', 'keyword_name' => 'Hyperpigmentation'],
    ];

    private const RATING_OPTIONS = [
        ['label' => 'Note de 5 étoiles', 'value' => 'rating-5', 'keyword_name' => 'Note de 5 étoiles', 'score' => 5],
        ['label' => 'Note de 4 étoiles', 'value' => 'rating-4', 'keyword_name' => 'Note de 4 étoiles', 'score' => 4],
        ['label' => 'Note de 3 étoiles', 'value' => 'rating-3', 'keyword_name' => 'Note de 3 étoiles', 'score' => 3],
        ['label' => 'Note de 2 étoiles', 'value' => 'rating-2', 'keyword_name' => 'Note de 2 étoiles', 'score' => 2],
        ['label' => 'Note de 1 étoile', 'value' => 'rating-1', 'keyword_name' => 'Note de 1 étoile', 'score' => 1],
    ];

    public function __construct(
        private readonly SsCategoryRepository $ssCategoryRepository,
        private readonly KeywordRepository $keywordRepository,
    ) {
    }

    public function getProductTypeOptions(): array
    {
        return self::PRODUCT_TYPES;
    }

    public function getProblematicOptions(): array
    {
        return self::PROBLEMATIC_OPTIONS;
    }

    public function getRatingOptions(): array
    {
        return self::RATING_OPTIONS;
    }

    public function sanitizeSort(?string $sort): ?string
    {
        $allowed = [
            self::SORT_BEST_SELLERS,
            self::SORT_PRICE_ASC,
            self::SORT_PRICE_DESC,
            self::SORT_RATING_DESC,
        ];

        return in_array($sort, $allowed, true) ? $sort : null;
    }

    /**
     * Retourne les ID des sous-catégories correspondant aux valeurs sélectionnées.
     *
     * @param string[] $selectedValues
     * @return array{idList: int[], values: string[]}
     */
    public function resolveProductTypes(array $selectedValues): array
    {
        $allowedValues = array_column(self::PRODUCT_TYPES, 'value');
        $cleanValues = array_values(array_intersect($selectedValues, $allowedValues));

        if ($cleanValues === []) {
            return ['idList' => [], 'values' => []];
        }

        $items = $this->ssCategoryRepository->createQueryBuilder('ss')
            ->select('ss.id, ss.slug')
            ->where('ss.slug IN (:slugs)')
            ->setParameter('slugs', $cleanValues)
            ->getQuery()
            ->getResult();

        $ids = array_map(static fn (array $row): int => (int) $row['id'], $items);

        return ['idList' => $ids, 'values' => $cleanValues];
    }

    /**
     * @param string[] $selectedValues
     * @return array{
     *     idList: int[],
     *     values: string[],
     *     keywordNames: string[]
     * }
     */
    public function resolveProblematicKeywords(array $selectedValues): array
    {
        $lookup = $this->buildLookup(self::PROBLEMATIC_OPTIONS);
        $cleanValues = array_values(array_intersect($selectedValues, array_keys($lookup)));

        if ($cleanValues === []) {
            return ['idList' => [], 'values' => [], 'keywordNames' => []];
        }

        $names = array_map(static fn (string $value): string => $lookup[$value]['keyword_name'], $cleanValues);

        $items = $this->keywordRepository->createQueryBuilder('k')
            ->select('k.id, k.name')
            ->where('k.name IN (:names)')
            ->setParameter('names', $names)
            ->getQuery()
            ->getResult();

        $ids = array_map(static fn (array $row): int => (int) $row['id'], $items);

        return [
            'idList' => $ids,
            'values' => $cleanValues,
            'keywordNames' => $names,
        ];
    }

    /**
     * @param string[] $selectedValues
     * @return array{
     *     values: string[],
     *     keywordNames: string[],
     *     scoreMap: array<string,int>
     * }
     */
    public function resolveRatingFilters(array $selectedValues): array
    {
        $lookup = $this->buildLookup(self::RATING_OPTIONS);
        $cleanValues = array_values(array_intersect($selectedValues, array_keys($lookup)));

        if ($cleanValues === []) {
            return ['values' => [], 'keywordNames' => [], 'scoreMap' => []];
        }

        $names = array_map(static fn (string $value): string => $lookup[$value]['keyword_name'], $cleanValues);

        $scoreMap = [];
        foreach ($cleanValues as $value) {
            $option = $lookup[$value];
            $scoreMap[mb_strtolower($option['keyword_name'])] = (int) $option['score'];
        }

        return [
            'values' => $cleanValues,
            'keywordNames' => $names,
            'scoreMap' => $scoreMap,
        ];
    }

    public function hasActiveSelection(
        array $types,
        array $issues,
        array $ratings,
        ?string $sort
    ): bool {
        return $types !== [] || $issues !== [] || $ratings !== [] || $sort !== null;
    }

    /**
     * @param array<int, array<string, mixed>> $source
     * @return array<string, array<string, mixed>>
     */
    private function buildLookup(array $source): array
    {
        $lookup = [];
        foreach ($source as $item) {
            $lookup[$item['value']] = $item;
        }

        return $lookup;
    }
}



