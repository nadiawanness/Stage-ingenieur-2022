<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220808154715 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE core_user_core_organization');
        $this->addSql('ALTER TABLE core_user_organization DROP FOREIGN KEY FK_462A1642B57966A6');
        $this->addSql('DROP INDEX IDX_462A1642B57966A6 ON core_user_organization');
        $this->addSql('ALTER TABLE core_user_organization DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE core_user_organization CHANGE core_user_id core_user_additional_id INT NOT NULL');
        $this->addSql('ALTER TABLE core_user_organization ADD CONSTRAINT FK_462A16429A1570FB FOREIGN KEY (core_user_additional_id) REFERENCES core_user (id)');
        $this->addSql('CREATE INDEX IDX_462A16429A1570FB ON core_user_organization (core_user_additional_id)');
        $this->addSql('ALTER TABLE core_user_organization ADD PRIMARY KEY (core_user_additional_id, core_organization_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE core_user_core_organization (core_user_id INT NOT NULL, core_organization_id INT NOT NULL, INDEX IDX_CCCABCFDB57966A6 (core_user_id), INDEX IDX_CCCABCFD4F346186 (core_organization_id), PRIMARY KEY(core_user_id, core_organization_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE core_user_core_organization ADD CONSTRAINT FK_CCCABCFD4F346186 FOREIGN KEY (core_organization_id) REFERENCES core_organization (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE core_user_core_organization ADD CONSTRAINT FK_CCCABCFDB57966A6 FOREIGN KEY (core_user_id) REFERENCES core_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE core_user_organization DROP FOREIGN KEY FK_462A16429A1570FB');
        $this->addSql('DROP INDEX IDX_462A16429A1570FB ON core_user_organization');
        $this->addSql('ALTER TABLE core_user_organization DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE core_user_organization CHANGE core_user_additional_id core_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE core_user_organization ADD CONSTRAINT FK_462A1642B57966A6 FOREIGN KEY (core_user_id) REFERENCES core_user (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_462A1642B57966A6 ON core_user_organization (core_user_id)');
        $this->addSql('ALTER TABLE core_user_organization ADD PRIMARY KEY (core_user_id, core_organization_id)');
    }
}
