<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220823065643 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE core_country DROP borders, DROP languages, DROP translations, DROP regional_blocs');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE core_country ADD borders LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', ADD languages LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', ADD translations LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', ADD regional_blocs LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
    }
}
