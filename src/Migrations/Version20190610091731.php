<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190610091731 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE group_users (id INT AUTO_INCREMENT NOT NULL, admin_id INT DEFAULT NULL, avatar_fid INT DEFAULT NULL, cover_fid INT DEFAULT NULL, name TEXT NOT NULL, description LONGTEXT DEFAULT NULL, slug LONGTEXT NOT NULL, confidentiality VARCHAR(10) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_44AF8E8E642B8210 (admin_id), UNIQUE INDEX UNIQ_44AF8E8E3B1E5BE3 (avatar_fid), UNIQUE INDEX UNIQ_44AF8E8EFF6B0E46 (cover_fid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE group_users ADD CONSTRAINT FK_44AF8E8E642B8210 FOREIGN KEY (admin_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE group_users ADD CONSTRAINT FK_44AF8E8E3B1E5BE3 FOREIGN KEY (avatar_fid) REFERENCES file_manager (id)');
        $this->addSql('ALTER TABLE group_users ADD CONSTRAINT FK_44AF8E8EFF6B0E46 FOREIGN KEY (cover_fid) REFERENCES file_manager (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE group_users');
    }
}
