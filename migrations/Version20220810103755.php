<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220810103755 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE core_user_agencies (id INT AUTO_INCREMENT NOT NULL, core_user_additional_id INT DEFAULT NULL, core_agency_id INT NOT NULL, is_default TINYINT(1) NOT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_9E72439BB57966A69A1570FB (core_user_additional_id), INDEX IDX_9E72439B828AEB0E (core_agency_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE core_user_agencies ADD CONSTRAINT FK_9E72439BB57966A69A1570FB FOREIGN KEY (core_user_additional_id) REFERENCES core_user (id)');
        $this->addSql('ALTER TABLE core_user_agencies ADD CONSTRAINT FK_9E72439B828AEB0E FOREIGN KEY (core_agency_id) REFERENCES core_agency (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE core_user_agencies');
    }
}
