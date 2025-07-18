<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250718123452 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Comme la table existe déjà, on ne fait rien ici.
        // Si tu veux, tu peux même supprimer tout le contenu ici.
    }

    public function down(Schema $schema): void
    {
        // Ici pareil, tu peux décider de ne rien faire ou bien remettre les colonnes si tu les avais.
    }
}
