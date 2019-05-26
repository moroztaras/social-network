<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190522090525 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE dialogue (id INT AUTO_INCREMENT NOT NULL, creator_id INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_F18A1C3961220EA6 (creator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, sender_id INT DEFAULT NULL, receiver_id INT DEFAULT NULL, dialogue_id INT DEFAULT NULL, message LONGTEXT NOT NULL, created_at DATETIME NOT NULL, status INT NOT NULL, INDEX IDX_B6BD307FF624B39D (sender_id), INDEX IDX_B6BD307FCD53EDB6 (receiver_id), INDEX IDX_B6BD307FA6E12CBD (dialogue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dialogue ADD CONSTRAINT FK_F18A1C3961220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FCD53EDB6 FOREIGN KEY (receiver_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FA6E12CBD FOREIGN KEY (dialogue_id) REFERENCES dialogue (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FA6E12CBD');
        $this->addSql('DROP TABLE dialogue');
        $this->addSql('DROP TABLE message');
    }
}
