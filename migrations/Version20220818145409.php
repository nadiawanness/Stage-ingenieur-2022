<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220818145409 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE core_account_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, deadlines_account VARCHAR(255) NOT NULL, date_created DATETIME NOT NULL, expiration_date DATETIME DEFAULT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE core_country (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, top_level_domain VARCHAR(255) DEFAULT NULL, code VARCHAR(255) NOT NULL, alpha3_code VARCHAR(255) NOT NULL, calling_code VARCHAR(255) DEFAULT NULL, capital VARCHAR(255) DEFAULT NULL, alt VARCHAR(255) DEFAULT NULL, spelling VARCHAR(255) DEFAULT NULL, region VARCHAR(255) DEFAULT NULL, sub_region VARCHAR(255) DEFAULT NULL, population VARCHAR(255) DEFAULT NULL, numeric_code VARCHAR(255) DEFAULT NULL, latitude VARCHAR(255) DEFAULT NULL, longitude VARCHAR(255) DEFAULT NULL, demonym VARCHAR(255) DEFAULT NULL, area VARCHAR(255) DEFAULT NULL, gini VARCHAR(255) DEFAULT NULL, timezones VARCHAR(255) DEFAULT NULL, borders LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', native_name VARCHAR(255) DEFAULT NULL, currency VARCHAR(255) DEFAULT NULL, languages LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', translations LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', flag VARCHAR(255) DEFAULT NULL, regional_blocs LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', cioc VARCHAR(255) DEFAULT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE core_role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, visibility VARCHAR(255) DEFAULT NULL, is_default TINYINT(1) NOT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE core_account_type');
        $this->addSql('DROP TABLE core_country');
        $this->addSql('DROP TABLE core_role');
    }
}
