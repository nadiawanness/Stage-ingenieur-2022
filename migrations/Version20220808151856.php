<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220808151856 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE core_user_organization (core_user_id INT NOT NULL, core_organization_id INT NOT NULL, INDEX IDX_462A1642B57966A6 (core_user_id), INDEX IDX_462A16424F346186 (core_organization_id), PRIMARY KEY(core_user_id, core_organization_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE core_user_organization ADD CONSTRAINT FK_462A1642B57966A6 FOREIGN KEY (core_user_id) REFERENCES core_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE core_user_organization ADD CONSTRAINT FK_462A16424F346186 FOREIGN KEY (core_organization_id) REFERENCES core_organization (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE core_user_core_organization');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE core_user_core_organization (core_user_id INT NOT NULL, core_organization_id INT NOT NULL, INDEX IDX_CCCABCFDB57966A6 (core_user_id), INDEX IDX_CCCABCFD4F346186 (core_organization_id), PRIMARY KEY(core_user_id, core_organization_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE core_user_core_organization ADD CONSTRAINT FK_CCCABCFD4F346186 FOREIGN KEY (core_organization_id) REFERENCES core_organization (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE core_user_core_organization ADD CONSTRAINT FK_CCCABCFDB57966A6 FOREIGN KEY (core_user_id) REFERENCES core_user (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE core_user_organization');
    }
}
