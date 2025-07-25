<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250725151143 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE address ADD last_name VARCHAR(255) NOT NULL, ADD first_name VARCHAR(255) NOT NULL, ADD address VARCHAR(255) NOT NULL, ADD postale_code VARCHAR(255) NOT NULL, ADD city VARCHAR(255) NOT NULL, ADD country VARCHAR(255) NOT NULL, ADD phone VARCHAR(255) NOT NULL, DROP nom, DROP prenom, DROP adresse, DROP ville, DROP code_postal, DROP pays, DROP telephone, CHANGE entreprise company VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE address ADD CONSTRAINT FK_D4E6F81A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D4E6F81A76ED395 ON address (user_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_D4E6F81A76ED395 ON address
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE address ADD nom VARCHAR(255) NOT NULL, ADD prenom VARCHAR(255) NOT NULL, ADD adresse VARCHAR(255) NOT NULL, ADD ville VARCHAR(255) NOT NULL, ADD code_postal VARCHAR(255) NOT NULL, ADD pays VARCHAR(255) NOT NULL, ADD telephone VARCHAR(255) NOT NULL, DROP last_name, DROP first_name, DROP address, DROP postale_code, DROP city, DROP country, DROP phone, CHANGE company entreprise VARCHAR(255) DEFAULT NULL
        SQL);
    }
}
