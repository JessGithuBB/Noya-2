<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250730163513 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_BFDD316877153098 ON articles (code)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_DFBCF38A5E237E06 ON ss_category (name)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_DFBCF38A989D9B62 ON ss_category (slug)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_BFDD316877153098 ON articles
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_DFBCF38A5E237E06 ON ss_category
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_DFBCF38A989D9B62 ON ss_category
        SQL);
    }
}
