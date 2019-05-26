<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190522123125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE dialogue ADD receiver_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dialogue ADD CONSTRAINT FK_F18A1C39CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_F18A1C39CD53EDB6 ON dialogue (receiver_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE dialogue DROP FOREIGN KEY FK_F18A1C39CD53EDB6');
        $this->addSql('DROP INDEX IDX_F18A1C39CD53EDB6 ON dialogue');
        $this->addSql('ALTER TABLE dialogue DROP receiver_id');
    }
}
