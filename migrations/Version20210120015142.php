<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210120015142 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE config (id INT AUTO_INCREMENT NOT NULL, forum_rules LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_replies (id INT AUTO_INCREMENT NOT NULL, posted_by_id INT DEFAULT NULL, thread_id INT NOT NULL, post_body LONGTEXT NOT NULL, posted_date DATETIME NOT NULL, INDEX IDX_51CC2A5B5A6D2235 (posted_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_threads (id INT AUTO_INCREMENT NOT NULL, posted_by_id INT DEFAULT NULL, board_id INT NOT NULL, thread_title VARCHAR(255) NOT NULL, post_body LONGTEXT NOT NULL, posted_date DATETIME NOT NULL, last_updated DATETIME DEFAULT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_9E4270AC5A6D2235 (posted_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forums (id INT AUTO_INCREMENT NOT NULL, forum_name VARCHAR(255) NOT NULL, forum_description LONGTEXT NOT NULL, forum_icon VARCHAR(255) DEFAULT NULL, access VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messages (id INT AUTO_INCREMENT NOT NULL, to_user_id INT DEFAULT NULL, from_user_id INT DEFAULT NULL, message_type LONGTEXT NOT NULL, message_topic LONGTEXT DEFAULT NULL, message_body LONGTEXT DEFAULT NULL, message_sent DATETIME NOT NULL, INDEX IDX_DB021E9629F6EE60 (to_user_id), INDEX IDX_DB021E962130303A (from_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, display_name VARCHAR(180) DEFAULT \'New Player\', roles JSON NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, profile LONGTEXT DEFAULT NULL, money INT DEFAULT 50000 NOT NULL, credits INT DEFAULT 0 NOT NULL, avatar VARCHAR(225) DEFAULT NULL, status VARCHAR(225) DEFAULT \'N00b.\', stable VARCHAR(225) DEFAULT \'New Stable\', stalls INT DEFAULT 0 NOT NULL, board INT DEFAULT 0 NOT NULL, joined DATETIME DEFAULT NULL, days INT DEFAULT 0 NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE forum_replies ADD CONSTRAINT FK_51CC2A5B5A6D2235 FOREIGN KEY (posted_by_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE forum_threads ADD CONSTRAINT FK_9E4270AC5A6D2235 FOREIGN KEY (posted_by_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E9629F6EE60 FOREIGN KEY (to_user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E962130303A FOREIGN KEY (from_user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE forum_replies DROP FOREIGN KEY FK_51CC2A5B5A6D2235');
        $this->addSql('ALTER TABLE forum_threads DROP FOREIGN KEY FK_9E4270AC5A6D2235');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E9629F6EE60');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E962130303A');
        $this->addSql('DROP TABLE config');
        $this->addSql('DROP TABLE forum_replies');
        $this->addSql('DROP TABLE forum_threads');
        $this->addSql('DROP TABLE forums');
        $this->addSql('DROP TABLE messages');
        $this->addSql('DROP TABLE `user`');
    }
}
