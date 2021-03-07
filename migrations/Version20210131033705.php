<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210131033705 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE horses ADD breed_id INT DEFAULT NULL, DROP breed');
        $this->addSql('ALTER TABLE horses ADD CONSTRAINT FK_800CD72A8B4A30F FOREIGN KEY (breed_id) REFERENCES breeds (id)');
        $this->addSql('CREATE INDEX IDX_800CD72A8B4A30F ON horses (breed_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE horses DROP FOREIGN KEY FK_800CD72A8B4A30F');
        $this->addSql('DROP INDEX IDX_800CD72A8B4A30F ON horses');
        $this->addSql('ALTER TABLE horses ADD breed VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP breed_id');
    }
}
