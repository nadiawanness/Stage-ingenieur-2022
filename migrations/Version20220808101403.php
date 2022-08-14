<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220808101403 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE core_organization (id INT AUTO_INCREMENT NOT NULL, company_number VARCHAR(255) NOT NULL, company_name VARCHAR(255) NOT NULL, vat INT DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, nomenclature VARCHAR(255) DEFAULT NULL, created_by VARCHAR(255) DEFAULT NULL, branch VARCHAR(255) DEFAULT NULL, branch_status VARCHAR(255) DEFAULT NULL, current_status VARCHAR(255) DEFAULT NULL, company_type VARCHAR(255) DEFAULT NULL, dissolution_date DATETIME DEFAULT NULL, inactive TINYINT(1) DEFAULT NULL, incorporation_date DATETIME DEFAULT NULL, industry_codes LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', jurisdiction_code VARCHAR(255) DEFAULT NULL, native_company_number VARCHAR(255) DEFAULT NULL, open_corporates_url VARCHAR(255) DEFAULT NULL, previous_names LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', registry_url VARCHAR(255) DEFAULT NULL, restricted_for_marketing VARCHAR(255) DEFAULT NULL, source LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', logo VARCHAR(255) DEFAULT NULL, custom_translation_path VARCHAR(255) DEFAULT NULL, enabled TINYINT(1) NOT NULL, retrived_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE core_organization');
    }
}
