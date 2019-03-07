<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190307162700 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_57698A6A57698A6A (role), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, avatar_fid INT DEFAULT NULL, cover_fid INT DEFAULT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, status SMALLINT NOT NULL, roles JSON NOT NULL, fullname VARCHAR(255) NOT NULL, birthday DATETIME NOT NULL, gender VARCHAR(10) NOT NULL, region VARCHAR(255) DEFAULT NULL, token_recover VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D6493B1E5BE3 (avatar_fid), UNIQUE INDEX UNIQ_8D93D649FF6B0E46 (cover_fid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE svistyn (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, photo_id INT DEFAULT NULL, parent_id INT DEFAULT NULL, text TEXT DEFAULT NULL, embed_video VARCHAR(255) DEFAULT NULL, state INT NOT NULL, status INT NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, marking VARCHAR(255) NOT NULL, INDEX IDX_7730AF36A76ED395 (user_id), INDEX IDX_7730AF367E9E4C8C (photo_id), INDEX IDX_7730AF36727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE friends (id INT AUTO_INCREMENT NOT NULL, user INT NOT NULL, friend INT NOT NULL, created DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE file_usage (id INT AUTO_INCREMENT NOT NULL, file_id INT DEFAULT NULL, origin_id INT DEFAULT NULL, entity_id VARCHAR(255) DEFAULT NULL, entity_type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_7A68EE4793CB796C (file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE file_manager (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, filename VARCHAR(255) NOT NULL, origin_name VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, file_size INT NOT NULL, file_mime VARCHAR(255) NOT NULL, status SMALLINT NOT NULL, created DATETIME NOT NULL, handler VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6493B1E5BE3 FOREIGN KEY (avatar_fid) REFERENCES file_manager (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649FF6B0E46 FOREIGN KEY (cover_fid) REFERENCES file_manager (id)');
        $this->addSql('ALTER TABLE svistyn ADD CONSTRAINT FK_7730AF36A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE svistyn ADD CONSTRAINT FK_7730AF367E9E4C8C FOREIGN KEY (photo_id) REFERENCES file_manager (id)');
        $this->addSql('ALTER TABLE svistyn ADD CONSTRAINT FK_7730AF36727ACA70 FOREIGN KEY (parent_id) REFERENCES svistyn (id)');
        $this->addSql('ALTER TABLE file_usage ADD CONSTRAINT FK_7A68EE4793CB796C FOREIGN KEY (file_id) REFERENCES file_manager (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE svistyn DROP FOREIGN KEY FK_7730AF36A76ED395');
        $this->addSql('ALTER TABLE svistyn DROP FOREIGN KEY FK_7730AF36727ACA70');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6493B1E5BE3');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649FF6B0E46');
        $this->addSql('ALTER TABLE svistyn DROP FOREIGN KEY FK_7730AF367E9E4C8C');
        $this->addSql('ALTER TABLE file_usage DROP FOREIGN KEY FK_7A68EE4793CB796C');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE svistyn');
        $this->addSql('DROP TABLE friends');
        $this->addSql('DROP TABLE file_usage');
        $this->addSql('DROP TABLE file_manager');
    }
}
