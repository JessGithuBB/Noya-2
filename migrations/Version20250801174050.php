<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250801174050 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE category DROP ss_category_id, DROP articles_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ss_category CHANGE description description LONGTEXT DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE category ADD ss_category_id VARCHAR(255) DEFAULT NULL, ADD articles_id BIGINT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ss_category CHANGE description description LONGTEXT NOT NULL
        SQL);
    }
}
