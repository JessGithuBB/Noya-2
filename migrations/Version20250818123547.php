<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250818123547 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE article_image (id INT AUTO_INCREMENT NOT NULL, article_id INT NOT NULL, image_name VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_B28A764E7294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE articles (id INT AUTO_INCREMENT NOT NULL, user_id BIGINT DEFAULT NULL, code VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, short_description LONGTEXT NOT NULL, long_description LONGTEXT DEFAULT NULL, brand VARCHAR(255) NOT NULL, selling_price DOUBLE PRECISION NOT NULL, quantity BIGINT NOT NULL, is_new_arrival TINYINT(1) NOT NULL, is_best_seller TINYINT(1) NOT NULL, is_available TINYINT(1) NOT NULL, image VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', UNIQUE INDEX UNIQ_BFDD316877153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', UNIQUE INDEX UNIQ_64C19C15E237E06 (name), UNIQUE INDEX UNIQ_64C19C1989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE ss_category (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', UNIQUE INDEX UNIQ_DFBCF38A5E237E06 (name), UNIQUE INDEX UNIQ_DFBCF38A989D9B62 (slug), INDEX IDX_DFBCF38A12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE article_image ADD CONSTRAINT FK_B28A764E7294869C FOREIGN KEY (article_id) REFERENCES articles (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ss_category ADD CONSTRAINT FK_DFBCF38A12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE account_deletion_request DROP FOREIGN KEY FK_31051CD6A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE account_deletion_request
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE deletion_audit
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP pending_deletion_at, DROP avatar
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE account_deletion_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, token VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, scheduled_at DATETIME NOT NULL, cancelled_at DATETIME DEFAULT NULL, admin_forced TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_31051CD6A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE deletion_audit (id INT AUTO_INCREMENT NOT NULL, user_hash VARCHAR(64) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, deleted_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE account_deletion_request ADD CONSTRAINT FK_31051CD6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE article_image DROP FOREIGN KEY FK_B28A764E7294869C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ss_category DROP FOREIGN KEY FK_DFBCF38A12469DE2
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE article_image
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE articles
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE category
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE ss_category
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD pending_deletion_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', ADD avatar VARCHAR(255) DEFAULT NULL
        SQL);
    }
}
