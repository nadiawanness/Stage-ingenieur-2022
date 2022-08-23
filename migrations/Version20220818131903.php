<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220818131903 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE core_user_agency');
        $this->addSql('ALTER TABLE core_agency DROP FOREIGN KEY FK_D09CA5BF61220EA6');
        $this->addSql('DROP INDEX IDX_D09CA5BF61220EA6 ON core_agency');
        $this->addSql('ALTER TABLE core_agency DROP creator_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE core_user_agency (core_user_additional_id INT NOT NULL, core_agency_id INT NOT NULL, INDEX IDX_C2A343B19A1570FB (core_user_additional_id), INDEX IDX_C2A343B1828AEB0E (core_agency_id), PRIMARY KEY(core_user_additional_id, core_agency_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE core_user_agency ADD CONSTRAINT FK_C2A343B1828AEB0E FOREIGN KEY (core_agency_id) REFERENCES core_agency (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE core_user_agency ADD CONSTRAINT FK_C2A343B19A1570FB FOREIGN KEY (core_user_additional_id) REFERENCES core_user (id)');
        $this->addSql('ALTER TABLE core_agency ADD creator_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE core_agency ADD CONSTRAINT FK_D09CA5BF61220EA6 FOREIGN KEY (creator_id) REFERENCES core_user (id)');
        $this->addSql('CREATE INDEX IDX_D09CA5BF61220EA6 ON core_agency (creator_id)');
    }
}
