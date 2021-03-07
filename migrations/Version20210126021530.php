<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210126021530 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE breeds (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, ext_min INT NOT NULL, ext_max INT NOT NULL, ag_min INT NOT NULL, ag_max INT NOT NULL, grey_min INT NOT NULL, grey_max INT NOT NULL, cream_min INT NOT NULL, cream_max INT NOT NULL, pearl_min INT NOT NULL, pearl_max INT NOT NULL, dun_min INT NOT NULL, dun_max INT NOT NULL, champ_min INT NOT NULL, champ_max INT NOT NULL, flax_min INT NOT NULL, flax_max INT NOT NULL, silver_min INT NOT NULL, silver_max INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE breeds');
    }
}
