<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210120203705 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bank_transactions ADD to_user_id INT DEFAULT NULL, ADD from_user_id INT DEFAULT NULL, DROP to_user, DROP from_user');
        $this->addSql('ALTER TABLE bank_transactions ADD CONSTRAINT FK_2A30FD5729F6EE60 FOREIGN KEY (to_user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE bank_transactions ADD CONSTRAINT FK_2A30FD572130303A FOREIGN KEY (from_user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_2A30FD5729F6EE60 ON bank_transactions (to_user_id)');
        $this->addSql('CREATE INDEX IDX_2A30FD572130303A ON bank_transactions (from_user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bank_transactions DROP FOREIGN KEY FK_2A30FD5729F6EE60');
        $this->addSql('ALTER TABLE bank_transactions DROP FOREIGN KEY FK_2A30FD572130303A');
        $this->addSql('DROP INDEX IDX_2A30FD5729F6EE60 ON bank_transactions');
        $this->addSql('DROP INDEX IDX_2A30FD572130303A ON bank_transactions');
        $this->addSql('ALTER TABLE bank_transactions ADD to_user INT NOT NULL, ADD from_user INT NOT NULL, DROP to_user_id, DROP from_user_id');
    }
}
