<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220810104916 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_9E72439BB57966A69A1570FB ON core_user_agencies');
        $this->addSql('ALTER TABLE core_user_agencies ADD core_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE core_user_agencies ADD CONSTRAINT FK_9E72439BB57966A69A1570FB FOREIGN KEY (core_user_id, core_user_additional_id) REFERENCES core_user (id)');
        $this->addSql('ALTER TABLE core_user_agencies ADD CONSTRAINT FK_9E72439B828AEB0E FOREIGN KEY (core_agency_id) REFERENCES core_agency (id)');
        $this->addSql('CREATE INDEX IDX_9E72439BB57966A69A1570FB ON core_user_agencies (core_user_id, core_user_additional_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE core_user_agencies DROP FOREIGN KEY FK_9E72439BB57966A69A1570FB');
        $this->addSql('ALTER TABLE core_user_agencies DROP FOREIGN KEY FK_9E72439B828AEB0E');
        $this->addSql('DROP INDEX IDX_9E72439BB57966A69A1570FB ON core_user_agencies');
        $this->addSql('ALTER TABLE core_user_agencies DROP core_user_id');
        $this->addSql('CREATE INDEX IDX_9E72439BB57966A69A1570FB ON core_user_agencies (core_user_additional_id)');
    }
}
