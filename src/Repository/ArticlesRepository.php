<?php

namespace App\Repository;

use App\Entity\Articles;
use App\Service\CatalogFilterService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Articles>
 */
class ArticlesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Articles::class);
    }

    /**
     * Recherche les articles en fonction d'un ensemble de filtres/tri.
     *
     * @param int[]    $ssCategoryIds
     * @param int[]    $issueKeywordIds
     * @param string[] $ratingKeywordNames Normalisés (lowercase)
     * @param string[] $ratingScoreMap     Assoc nom(normalisé) => score
     *
     * @return Articles[]
     */
    public function findWithFilters(
        array $ssCategoryIds = [],
        array $issueKeywordIds = [],
        array $ratingKeywordNames = [],
        ?string $sort = null,
        array $ratingScoreMap = [],
        bool $bestSellersOnly = false
    ): array {
        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.ssCategories', 'ssc')
            ->addSelect('ssc')
            ->leftJoin('a.keywords', 'k')
            ->addSelect('k')
            ->where('a.is_available = :available')
            ->setParameter('available', true)
            ->distinct();

        if ($ssCategoryIds !== []) {
            $qb->andWhere('ssc.id IN (:ssCategoryIds)')
                ->setParameter('ssCategoryIds', $ssCategoryIds);
        }

        if ($bestSellersOnly) {
            $qb->andWhere('a.is_best_seller = :bestSeller')
                ->setParameter('bestSeller', true);
        }

        if ($sort === CatalogFilterService::SORT_PRICE_ASC) {
            $qb->orderBy('a.selling_price', 'ASC');
        } elseif ($sort === CatalogFilterService::SORT_PRICE_DESC) {
            $qb->orderBy('a.selling_price', 'DESC');
        } elseif ($sort === CatalogFilterService::SORT_RATING_DESC && $ratingScoreMap !== []) {
            $qb->addSelect(
                'MAX(CASE ' . $this->buildRatingCaseExpression($ratingScoreMap, 'k.name') . ' ELSE 0 END) AS HIDDEN rating_value'
            );
            $qb->orderBy('rating_value', 'DESC');
        } else {
            $qb->orderBy('a.created_at', 'DESC');
        }

        $articles = $qb->getQuery()->getResult();

        if ($issueKeywordIds !== []) {
            $articles = array_filter(
                $articles,
                static function (Articles $article) use ($issueKeywordIds): bool {
                    foreach ($article->getKeywords() as $keyword) {
                        if (in_array($keyword->getId(), $issueKeywordIds, true)) {
                            return true;
                        }
                    }
                    return false;
                }
            );
        }

        if ($ratingKeywordNames !== []) {
            $articles = array_filter(
                $articles,
                static function (Articles $article) use ($ratingKeywordNames): bool {
                    foreach ($article->getKeywords() as $keyword) {
                        if (in_array(mb_strtolower($keyword->getName()), $ratingKeywordNames, true)) {
                            return true;
                        }
                    }
                    return false;
                }
            );
        }

        if ($sort === CatalogFilterService::SORT_RATING_DESC && $ratingScoreMap !== []) {
            $normalizedMap = $this->normalizeRatingScoreMap($ratingScoreMap);
            usort(
                $articles,
                static function (Articles $a, Articles $b) use ($normalizedMap): int {
                    $ratingA = self::resolveArticleRatingScore($a, $normalizedMap);
                    $ratingB = self::resolveArticleRatingScore($b, $normalizedMap);

                    return $ratingB <=> $ratingA;
                }
            );
        }

        return array_values($articles);
    }

    /**
     * @param array<string,int> $ratingScoreMap
     */
    private function buildRatingCaseExpression(array $ratingScoreMap, string $field): string
    {
        $cases = [];
        foreach ($ratingScoreMap as $label => $score) {
            $escaped = str_replace("'", "''", $label);
            $cases[] = sprintf("WHEN LOWER(%s) = '%s' THEN %d", $field, $escaped, $score);
        }

        return implode(' ', $cases);
    }

    /**
     * @param array<string,int> $map
     * @return array<string,int>
     */
    private function normalizeRatingScoreMap(array $map): array
    {
        $normalized = [];
        foreach ($map as $label => $score) {
            $normalized[mb_strtolower($label)] = (int) $score;
        }

        return $normalized;
    }

    /**
     * @param array<string,int> $map
     */
    private static function resolveArticleRatingScore(Articles $article, array $map): int
    {
        $score = 0;
        foreach ($article->getKeywords() as $keyword) {
            $key = mb_strtolower($keyword->getName());
            if (isset($map[$key])) {
                $score = max($score, $map[$key]);
            }
        }

        return $score;
    }
}
