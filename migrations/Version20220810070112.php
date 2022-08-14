<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220810070112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE core_agency (id INT AUTO_INCREMENT NOT NULL, internal_code INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, siret VARCHAR(255) NOT NULL, activity VARCHAR(255) DEFAULT NULL, id_erp VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, logo VARCHAR(255) DEFAULT NULL, header VARCHAR(255) DEFAULT NULL, footer VARCHAR(255) DEFAULT NULL, organization_type VARCHAR(255) DEFAULT NULL, total_items_count INT NOT NULL, is_default TINYINT(1) NOT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE core_organization DROP previous_names, DROP source');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE core_agency');
        $this->addSql('ALTER TABLE core_organization ADD previous_names LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', ADD source LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\'');
    }
}
