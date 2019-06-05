<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190316095607 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE friends DROP INDEX UNIQ_21EE70696A5458E8, ADD INDEX IDX_21EE70696A5458E8 (friend_id)');
        $this->addSql('ALTER TABLE friends DROP INDEX UNIQ_21EE7069A76ED395, ADD INDEX IDX_21EE7069A76ED395 (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE friends DROP INDEX IDX_21EE70696A5458E8, ADD UNIQUE INDEX UNIQ_21EE70696A5458E8 (friend_id)');
        $this->addSql('ALTER TABLE friends DROP INDEX IDX_21EE7069A76ED395, ADD UNIQUE INDEX UNIQ_21EE7069A76ED395 (user_id)');
    }
}
