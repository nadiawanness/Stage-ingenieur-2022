<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220829104805 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE access_token ADD core_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE access_token ADD CONSTRAINT FK_B6A2DD68B57966A6 FOREIGN KEY (core_user_id) REFERENCES core_user (id)');
        $this->addSql('CREATE INDEX IDX_B6A2DD68B57966A6 ON access_token (core_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE access_token DROP FOREIGN KEY FK_B6A2DD68B57966A6');
        $this->addSql('DROP INDEX IDX_B6A2DD68B57966A6 ON access_token');
        $this->addSql('ALTER TABLE access_token DROP core_user_id');
    }
}
