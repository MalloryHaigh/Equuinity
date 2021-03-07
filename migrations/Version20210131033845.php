<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210131033845 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE horses ADD owner_id INT DEFAULT NULL, ADD breeder_id INT DEFAULT NULL, DROP owner, DROP breeder');
        $this->addSql('ALTER TABLE horses ADD CONSTRAINT FK_800CD727E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE horses ADD CONSTRAINT FK_800CD7233C95BB1 FOREIGN KEY (breeder_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_800CD727E3C61F9 ON horses (owner_id)');
        $this->addSql('CREATE INDEX IDX_800CD7233C95BB1 ON horses (breeder_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE horses DROP FOREIGN KEY FK_800CD727E3C61F9');
        $this->addSql('ALTER TABLE horses DROP FOREIGN KEY FK_800CD7233C95BB1');
        $this->addSql('DROP INDEX IDX_800CD727E3C61F9 ON horses');
        $this->addSql('DROP INDEX IDX_800CD7233C95BB1 ON horses');
        $this->addSql('ALTER TABLE horses ADD owner INT NOT NULL, ADD breeder INT NOT NULL, DROP owner_id, DROP breeder_id');
    }
}
