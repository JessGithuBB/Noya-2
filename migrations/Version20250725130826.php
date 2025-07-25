<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250725130826 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_D4E6F81A76ED395 ON address
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE address ADD valeur VARCHAR(255) NOT NULL, ADD nom VARCHAR(255) NOT NULL, ADD prenom VARCHAR(255) NOT NULL, ADD adresse VARCHAR(255) NOT NULL, ADD ville VARCHAR(255) NOT NULL, ADD code_postal VARCHAR(255) NOT NULL, ADD pays VARCHAR(255) NOT NULL, ADD keyword VARCHAR(255) DEFAULT NULL, ADD rue VARCHAR(255) NOT NULL, ADD telephone VARCHAR(255) NOT NULL, ADD email VARCHAR(255) DEFAULT NULL, ADD entreprise VARCHAR(255) DEFAULT NULL, ADD details LONGTEXT DEFAULT NULL, ADD created_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', ADD updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', DROP street, DROP address_complement, DROP postal_code, DROP city, DROP country
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE address ADD street VARCHAR(255) DEFAULT NULL, ADD address_complement VARCHAR(255) DEFAULT NULL, ADD postal_code VARCHAR(20) DEFAULT NULL, ADD city VARCHAR(255) DEFAULT NULL, ADD country VARCHAR(255) DEFAULT NULL, DROP valeur, DROP nom, DROP prenom, DROP adresse, DROP ville, DROP code_postal, DROP pays, DROP keyword, DROP rue, DROP telephone, DROP email, DROP entreprise, DROP details, DROP created_at, DROP updated_at
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE address ADD CONSTRAINT FK_D4E6F81A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D4E6F81A76ED395 ON address (user_id)
        SQL);
    }
}
