<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220810113130 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE core_user_agency (core_user_additional_id INT NOT NULL, core_agency_id INT NOT NULL, INDEX IDX_C2A343B19A1570FB (core_user_additional_id), INDEX IDX_C2A343B1828AEB0E (core_agency_id), PRIMARY KEY(core_user_additional_id, core_agency_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE core_user_agency ADD CONSTRAINT FK_C2A343B19A1570FB FOREIGN KEY (core_user_additional_id) REFERENCES core_user (id)');
        $this->addSql('ALTER TABLE core_user_agency ADD CONSTRAINT FK_C2A343B1828AEB0E FOREIGN KEY (core_agency_id) REFERENCES core_agency (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE core_user_core_agency');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE core_user_core_agency (core_user_id INT NOT NULL, core_agency_id INT NOT NULL, INDEX IDX_1C4A492EB57966A6 (core_user_id), INDEX IDX_1C4A492E828AEB0E (core_agency_id), PRIMARY KEY(core_user_id, core_agency_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE core_user_core_agency ADD CONSTRAINT FK_1C4A492E828AEB0E FOREIGN KEY (core_agency_id) REFERENCES core_agency (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE core_user_core_agency ADD CONSTRAINT FK_1C4A492EB57966A6 FOREIGN KEY (core_user_id) REFERENCES core_user (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE core_user_agency');
    }
}
