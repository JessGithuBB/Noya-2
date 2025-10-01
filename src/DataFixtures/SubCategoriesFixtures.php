<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\SsCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\AsciiSlugger;

class SubCategoriesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $slugger = new AsciiSlugger();

        // Parents requis
        $parents = [
            'Soin de la peau' => 'soin-de-la-peau',
            'Corps et cheveux' => 'corps-et-cheveux',
            'Problématique cutanée' => 'problematique-cutanee',
        ];

        $parentEntities = [];
        foreach ($parents as $name => $slug) {
            $existing = $manager->getRepository(Category::class)->findOneBy(['slug' => $slug]);
            if (!$existing) {
                $existing = new Category();
                $existing->setName($name);
                $existing->setSlug($slug);
                $existing->setCreatedAt(new \DateTimeImmutable());
                $existing->setUpdatedAt(new \DateTimeImmutable());
                $manager->persist($existing);
            }
            $parentEntities[$slug] = $existing;
        }

        // Sous-catégories demandées
        $data = [
            'soin-de-la-peau' => [
                "Sérum" => 'serum',
                "Soins solaires" => 'soins-solaires',
                "Crème hydratante" => 'creme-hydratante',
                "Exfoliant" => 'exfoliant',
                "Sérum contour yeux" => 'serum-contour-yeux',
                "Nettoyant pour le visage" => 'nettoyant-pour-le-visage',
                "Huile pour le visage" => 'huile-pour-le-visage',
                "Tonique" => 'tonique',
            ],
            'corps-et-cheveux' => [
                "Huile pour cheveux" => 'huile-pour-cheveux',
                "Crème visage" => 'creme-visage',
            ],
            'problematique-cutanee' => [
                "Problème d'uniformité du teint" => 'probleme-uniformite-teint',
                "Texture de peau inégale" => 'texture-peau-inegale',
                "Sécheresse/excès de sébum" => 'secheresse-exces-sebum',
                "Vieillissement" => 'vieillissement',
            ],
        ];

        foreach ($data as $parentSlug => $children) {
            $parent = $parentEntities[$parentSlug] ?? null;
            if (!$parent) {
                continue;
            }

            foreach ($children as $name => $forcedSlug) {
                $existing = $manager->getRepository(SsCategory::class)->findOneBy(['slug' => $forcedSlug]);
                if ($existing) {
                    // Mettre à jour l'association parent si manquante
                    if ($existing->getCategory() === null) {
                        $existing->setCategory($parent);
                    }
                    continue;
                }

                $ss = new SsCategory();
                $ss->setName($name);
                $ss->setSlug($forcedSlug ?: strtolower($slugger->slug($name)));
                $ss->setCategory($parent);
                $ss->setCreatedAt(new \DateTimeImmutable());
                $ss->setUpdatedAt(new \DateTimeImmutable());
                $manager->persist($ss);
            }
        }

        $manager->flush();
    }
}


