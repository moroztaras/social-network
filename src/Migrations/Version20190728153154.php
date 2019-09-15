<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190728153154 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, avatar_fid INT DEFAULT NULL, cover_fid INT DEFAULT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, status SMALLINT NOT NULL, roles JSON NOT NULL, fullname VARCHAR(255) NOT NULL, birthday DATETIME NOT NULL, gender VARCHAR(10) NOT NULL, region VARCHAR(255) DEFAULT NULL, token_recover VARCHAR(255) DEFAULT NULL, api_token VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D6497BA2F5EB (api_token), UNIQUE INDEX UNIQ_8D93D6493B1E5BE3 (avatar_fid), UNIQUE INDEX UNIQ_8D93D649FF6B0E46 (cover_fid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_group_users (user_id INT NOT NULL, group_users_id INT NOT NULL, INDEX IDX_EDB4471BA76ED395 (user_id), INDEX IDX_EDB4471B6E83F842 (group_users_id), PRIMARY KEY(user_id, group_users_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE svistyn (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, photo_id INT DEFAULT NULL, parent_id INT DEFAULT NULL, group_users_id INT DEFAULT NULL, text TEXT DEFAULT NULL, embed_video VARCHAR(255) DEFAULT NULL, state INT NOT NULL, status INT NOT NULL, views INT NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, marking VARCHAR(255) NOT NULL, INDEX IDX_7730AF36A76ED395 (user_id), INDEX IDX_7730AF367E9E4C8C (photo_id), INDEX IDX_7730AF36727ACA70 (parent_id), INDEX IDX_7730AF366E83F842 (group_users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE group_users (id INT AUTO_INCREMENT NOT NULL, admin_id INT DEFAULT NULL, avatar_fid INT DEFAULT NULL, cover_fid INT DEFAULT NULL, name TEXT NOT NULL, description LONGTEXT DEFAULT NULL, slug LONGTEXT NOT NULL, confidentiality VARCHAR(10) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_44AF8E8E642B8210 (admin_id), UNIQUE INDEX UNIQ_44AF8E8E3B1E5BE3 (avatar_fid), UNIQUE INDEX UNIQ_44AF8E8EFF6B0E46 (cover_fid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, content_type VARCHAR(190) DEFAULT NULL, s3key LONGTEXT DEFAULT NULL, name_file VARCHAR(191) DEFAULT NULL, url LONGTEXT DEFAULT NULL, dtype VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, svistyn_id INT DEFAULT NULL, user_id INT DEFAULT NULL, comment LONGTEXT NOT NULL, created_at DATETIME NOT NULL, approved TINYINT(1) NOT NULL, INDEX IDX_9474526C49E1CCEF (svistyn_id), INDEX IDX_9474526CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dialogue (id INT AUTO_INCREMENT NOT NULL, creator_id INT DEFAULT NULL, receiver_id INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_F18A1C3961220EA6 (creator_id), INDEX IDX_F18A1C39CD53EDB6 (receiver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, sender_id INT DEFAULT NULL, receiver_id INT DEFAULT NULL, dialogue_id INT DEFAULT NULL, message VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, status INT NOT NULL, INDEX IDX_B6BD307FF624B39D (sender_id), INDEX IDX_B6BD307FCD53EDB6 (receiver_id), INDEX IDX_B6BD307FA6E12CBD (dialogue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE friends (id INT AUTO_INCREMENT NOT NULL, friend_id INT DEFAULT NULL, user_id INT DEFAULT NULL, status INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_21EE70696A5458E8 (friend_id), INDEX IDX_21EE7069A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE file_usage (id INT AUTO_INCREMENT NOT NULL, file_id INT DEFAULT NULL, origin_id INT DEFAULT NULL, entity_id VARCHAR(255) DEFAULT NULL, entity_type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_7A68EE4793CB796C (file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE group_users_request (id INT AUTO_INCREMENT NOT NULL, group_users_id INT DEFAULT NULL, user_id INT DEFAULT NULL, status_request VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_947E35176E83F842 (group_users_id), INDEX IDX_947E3517A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE file_manager (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, filename VARCHAR(255) NOT NULL, origin_name VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, file_size INT NOT NULL, file_mime VARCHAR(255) NOT NULL, status SMALLINT NOT NULL, created DATETIME NOT NULL, handler VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6493B1E5BE3 FOREIGN KEY (avatar_fid) REFERENCES file_manager (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649FF6B0E46 FOREIGN KEY (cover_fid) REFERENCES file_manager (id)');
        $this->addSql('ALTER TABLE user_group_users ADD CONSTRAINT FK_EDB4471BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_group_users ADD CONSTRAINT FK_EDB4471B6E83F842 FOREIGN KEY (group_users_id) REFERENCES group_users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE svistyn ADD CONSTRAINT FK_7730AF36A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE svistyn ADD CONSTRAINT FK_7730AF367E9E4C8C FOREIGN KEY (photo_id) REFERENCES file_manager (id)');
        $this->addSql('ALTER TABLE svistyn ADD CONSTRAINT FK_7730AF36727ACA70 FOREIGN KEY (parent_id) REFERENCES svistyn (id)');
        $this->addSql('ALTER TABLE svistyn ADD CONSTRAINT FK_7730AF366E83F842 FOREIGN KEY (group_users_id) REFERENCES group_users (id)');
        $this->addSql('ALTER TABLE group_users ADD CONSTRAINT FK_44AF8E8E642B8210 FOREIGN KEY (admin_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE group_users ADD CONSTRAINT FK_44AF8E8E3B1E5BE3 FOREIGN KEY (avatar_fid) REFERENCES file_manager (id)');
        $this->addSql('ALTER TABLE group_users ADD CONSTRAINT FK_44AF8E8EFF6B0E46 FOREIGN KEY (cover_fid) REFERENCES file_manager (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C49E1CCEF FOREIGN KEY (svistyn_id) REFERENCES svistyn (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE dialogue ADD CONSTRAINT FK_F18A1C3961220EA6 FOREIGN KEY (creator_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dialogue ADD CONSTRAINT FK_F18A1C39CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FCD53EDB6 FOREIGN KEY (receiver_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FA6E12CBD FOREIGN KEY (dialogue_id) REFERENCES dialogue (id)');
        $this->addSql('ALTER TABLE friends ADD CONSTRAINT FK_21EE70696A5458E8 FOREIGN KEY (friend_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE friends ADD CONSTRAINT FK_21EE7069A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE file_usage ADD CONSTRAINT FK_7A68EE4793CB796C FOREIGN KEY (file_id) REFERENCES file_manager (id)');
        $this->addSql('ALTER TABLE group_users_request ADD CONSTRAINT FK_947E35176E83F842 FOREIGN KEY (group_users_id) REFERENCES group_users (id)');
        $this->addSql('ALTER TABLE group_users_request ADD CONSTRAINT FK_947E3517A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_group_users DROP FOREIGN KEY FK_EDB4471BA76ED395');
        $this->addSql('ALTER TABLE svistyn DROP FOREIGN KEY FK_7730AF36A76ED395');
        $this->addSql('ALTER TABLE group_users DROP FOREIGN KEY FK_44AF8E8E642B8210');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE dialogue DROP FOREIGN KEY FK_F18A1C3961220EA6');
        $this->addSql('ALTER TABLE dialogue DROP FOREIGN KEY FK_F18A1C39CD53EDB6');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF624B39D');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FCD53EDB6');
        $this->addSql('ALTER TABLE friends DROP FOREIGN KEY FK_21EE70696A5458E8');
        $this->addSql('ALTER TABLE friends DROP FOREIGN KEY FK_21EE7069A76ED395');
        $this->addSql('ALTER TABLE group_users_request DROP FOREIGN KEY FK_947E3517A76ED395');
        $this->addSql('ALTER TABLE svistyn DROP FOREIGN KEY FK_7730AF36727ACA70');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C49E1CCEF');
        $this->addSql('ALTER TABLE user_group_users DROP FOREIGN KEY FK_EDB4471B6E83F842');
        $this->addSql('ALTER TABLE svistyn DROP FOREIGN KEY FK_7730AF366E83F842');
        $this->addSql('ALTER TABLE group_users_request DROP FOREIGN KEY FK_947E35176E83F842');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FA6E12CBD');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6493B1E5BE3');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649FF6B0E46');
        $this->addSql('ALTER TABLE svistyn DROP FOREIGN KEY FK_7730AF367E9E4C8C');
        $this->addSql('ALTER TABLE group_users DROP FOREIGN KEY FK_44AF8E8E3B1E5BE3');
        $this->addSql('ALTER TABLE group_users DROP FOREIGN KEY FK_44AF8E8EFF6B0E46');
        $this->addSql('ALTER TABLE file_usage DROP FOREIGN KEY FK_7A68EE4793CB796C');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_group_users');
        $this->addSql('DROP TABLE svistyn');
        $this->addSql('DROP TABLE group_users');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE dialogue');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE friends');
        $this->addSql('DROP TABLE file_usage');
        $this->addSql('DROP TABLE group_users_request');
        $this->addSql('DROP TABLE file_manager');
    }
}
