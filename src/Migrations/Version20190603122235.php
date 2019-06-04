<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190603122235 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE dialogue DROP FOREIGN KEY FK_F18A1C3961220EA6');
        $this->addSql('ALTER TABLE dialogue ADD CONSTRAINT FK_F18A1C3961220EA6 FOREIGN KEY (creator_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE message CHANGE message message VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE friends ADD status INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE dialogue DROP FOREIGN KEY FK_F18A1C3961220EA6');
        $this->addSql('ALTER TABLE dialogue ADD CONSTRAINT FK_F18A1C3961220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE friends DROP status');
        $this->addSql('ALTER TABLE message CHANGE message message LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
