<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220810103220 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_9E72439BB57966A69A1570FB ON core_user_agencies');
        $this->addSql('DROP INDEX IDX_9E72439B828AEB0E ON core_user_agencies');
        $this->addSql('ALTER TABLE core_user_agencies DROP core_user_id, DROP core_user_additional_id, DROP core_agency_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE core_user_agencies ADD core_user_id INT NOT NULL, ADD core_user_additional_id INT DEFAULT NULL, ADD core_agency_id INT NOT NULL');
        $this->addSql('CREATE INDEX IDX_9E72439BB57966A69A1570FB ON core_user_agencies (core_user_id, core_user_additional_id)');
        $this->addSql('CREATE INDEX IDX_9E72439B828AEB0E ON core_user_agencies (core_agency_id)');
    }
}
