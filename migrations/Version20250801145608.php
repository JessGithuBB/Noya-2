<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250801145608 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE ss_category ADD category_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ss_category ADD CONSTRAINT FK_DFBCF38A12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_DFBCF38A12469DE2 ON ss_category (category_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE ss_category DROP FOREIGN KEY FK_DFBCF38A12469DE2
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_DFBCF38A12469DE2 ON ss_category
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ss_category DROP category_id
        SQL);
    }
}
