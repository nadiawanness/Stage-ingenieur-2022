<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220829101813 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE access_token CHANGE single_use_token single_use_token VARCHAR(2000) DEFAULT NULL');
        $this->addSql('ALTER TABLE core_user CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE access_token CHANGE single_use_token single_use_token VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE core_user CHANGE roles roles JSON DEFAULT NULL');
    }
}
