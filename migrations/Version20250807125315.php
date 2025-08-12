<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250807125315 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE keyword (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE keyword_articles (keyword_id INT NOT NULL, articles_id INT NOT NULL, INDEX IDX_2EB6FC39115D4552 (keyword_id), INDEX IDX_2EB6FC391EBAF6CC (articles_id), PRIMARY KEY(keyword_id, articles_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE keyword_articles ADD CONSTRAINT FK_2EB6FC39115D4552 FOREIGN KEY (keyword_id) REFERENCES keyword (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE keyword_articles ADD CONSTRAINT FK_2EB6FC391EBAF6CC FOREIGN KEY (articles_id) REFERENCES articles (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE keyword_articles DROP FOREIGN KEY FK_2EB6FC39115D4552
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE keyword_articles DROP FOREIGN KEY FK_2EB6FC391EBAF6CC
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE keyword
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE keyword_articles
        SQL);
    }
}
