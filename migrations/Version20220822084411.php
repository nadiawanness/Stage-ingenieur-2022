<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220822084411 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE core_country ADD core_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE core_country ADD CONSTRAINT FK_41647C55B57966A6 FOREIGN KEY (core_user_id) REFERENCES core_user (id)');
        $this->addSql('CREATE INDEX IDX_41647C55B57966A6 ON core_country (core_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE core_country DROP FOREIGN KEY FK_41647C55B57966A6');
        $this->addSql('DROP INDEX IDX_41647C55B57966A6 ON core_country');
        $this->addSql('ALTER TABLE core_country DROP core_user_id');
    }
}
