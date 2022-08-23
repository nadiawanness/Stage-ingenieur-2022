<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220823082508 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE core_country CHANGE borders borders LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', CHANGE languages languages LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', CHANGE translations translations LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE core_country CHANGE borders borders LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', CHANGE languages languages LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', CHANGE translations translations LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\'');
    }
}
