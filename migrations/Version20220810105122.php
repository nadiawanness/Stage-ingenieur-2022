<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220810105122 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE core_agency (id INT AUTO_INCREMENT NOT NULL, internal_code INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, siret VARCHAR(255) NOT NULL, activity VARCHAR(255) DEFAULT NULL, id_erp VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, logo VARCHAR(255) DEFAULT NULL, header VARCHAR(255) DEFAULT NULL, footer VARCHAR(255) DEFAULT NULL, organization_type VARCHAR(255) DEFAULT NULL, total_items_count INT NOT NULL, is_default TINYINT(1) NOT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE core_organization (id INT AUTO_INCREMENT NOT NULL, company_number VARCHAR(255) NOT NULL, company_name VARCHAR(255) NOT NULL, vat INT DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, nomenclature VARCHAR(255) DEFAULT NULL, created_by VARCHAR(255) DEFAULT NULL, branch VARCHAR(255) DEFAULT NULL, branch_status VARCHAR(255) DEFAULT NULL, current_status VARCHAR(255) DEFAULT NULL, company_type VARCHAR(255) DEFAULT NULL, dissolution_date DATETIME DEFAULT NULL, inactive TINYINT(1) DEFAULT NULL, incorporation_date DATETIME DEFAULT NULL, jurisdiction_code VARCHAR(255) DEFAULT NULL, native_company_number VARCHAR(255) DEFAULT NULL, open_corporates_url VARCHAR(255) DEFAULT NULL, registry_url VARCHAR(255) DEFAULT NULL, restricted_for_marketing VARCHAR(255) DEFAULT NULL, logo VARCHAR(255) DEFAULT NULL, custom_translation_path VARCHAR(255) DEFAULT NULL, enabled TINYINT(1) NOT NULL, retrived_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE core_user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, username_canonical VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, email_canonical VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, salt VARCHAR(255) DEFAULT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', roles JSON NOT NULL, locale VARCHAR(255) DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, function_user VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, civility VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, id_erp VARCHAR(255) DEFAULT NULL, confirm_password VARCHAR(255) DEFAULT NULL, enabled TINYINT(1) NOT NULL, has_delegate TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE core_user_organization (core_user_additional_id INT NOT NULL, core_organization_id INT NOT NULL, INDEX IDX_462A16429A1570FB (core_user_additional_id), INDEX IDX_462A16424F346186 (core_organization_id), PRIMARY KEY(core_user_additional_id, core_organization_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE core_user_agencies (id INT AUTO_INCREMENT NOT NULL, core_user_id INT NOT NULL, core_user_additional_id INT DEFAULT NULL, core_agency_id INT NOT NULL, is_default TINYINT(1) NOT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_9E72439BB57966A69A1570FB (core_user_id, core_user_additional_id), INDEX IDX_9E72439B828AEB0E (core_agency_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE core_user_organization ADD CONSTRAINT FK_462A16429A1570FB FOREIGN KEY (core_user_additional_id) REFERENCES core_user (id)');
        $this->addSql('ALTER TABLE core_user_organization ADD CONSTRAINT FK_462A16424F346186 FOREIGN KEY (core_organization_id) REFERENCES core_organization (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE core_user_agencies ADD CONSTRAINT FK_9E72439BB57966A69A1570FB FOREIGN KEY (core_user_id, core_user_additional_id) REFERENCES core_user (id)');
        $this->addSql('ALTER TABLE core_user_agencies ADD CONSTRAINT FK_9E72439B828AEB0E FOREIGN KEY (core_agency_id) REFERENCES core_agency (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE core_user_agencies DROP FOREIGN KEY FK_9E72439B828AEB0E');
        $this->addSql('ALTER TABLE core_user_organization DROP FOREIGN KEY FK_462A16424F346186');
        $this->addSql('ALTER TABLE core_user_organization DROP FOREIGN KEY FK_462A16429A1570FB');
        $this->addSql('ALTER TABLE core_user_agencies DROP FOREIGN KEY FK_9E72439BB57966A69A1570FB');
        $this->addSql('DROP TABLE core_agency');
        $this->addSql('DROP TABLE core_organization');
        $this->addSql('DROP TABLE core_user');
        $this->addSql('DROP TABLE core_user_organization');
        $this->addSql('DROP TABLE core_user_agencies');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
