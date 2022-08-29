<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220826095727 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE core_user_role (id INT AUTO_INCREMENT NOT NULL, core_user_id INT DEFAULT NULL, core_role_id INT DEFAULT NULL, INDEX IDX_6288F687B57966A6 (core_user_id), INDEX IDX_6288F687C414979F (core_role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE core_user_role ADD CONSTRAINT FK_6288F687B57966A6 FOREIGN KEY (core_user_id) REFERENCES core_user (id)');
        $this->addSql('ALTER TABLE core_user_role ADD CONSTRAINT FK_6288F687C414979F FOREIGN KEY (core_role_id) REFERENCES core_role (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE core_user_role');
    }
}
