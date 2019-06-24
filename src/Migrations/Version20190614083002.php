<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190614083002 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE group_users_request (id INT AUTO_INCREMENT NOT NULL, group_users_id INT DEFAULT NULL, user_id INT DEFAULT NULL, status_request VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_947E35176E83F842 (group_users_id), INDEX IDX_947E3517A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE group_users_request ADD CONSTRAINT FK_947E35176E83F842 FOREIGN KEY (group_users_id) REFERENCES group_users (id)');
        $this->addSql('ALTER TABLE group_users_request ADD CONSTRAINT FK_947E3517A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE group_users_request');
    }
}
