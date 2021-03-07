<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210131035520 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE breeds ADD rabicano_min INT NOT NULL, ADD rabicano_max INT NOT NULL, ADD roan_min INT NOT NULL, ADD roan_max INT NOT NULL, ADD sabino_min INT NOT NULL, ADD sabino_max INT NOT NULL, ADD white_min INT NOT NULL, ADD white_max INT NOT NULL, ADD tovero_min INT NOT NULL, ADD tovero_max INT NOT NULL, ADD overo_min INT NOT NULL, ADD overo_max INT NOT NULL, ADD splash_min INT NOT NULL, ADD splash_max INT NOT NULL, ADD leopard_min INT NOT NULL, ADD leopard_max INT NOT NULL, ADD pattern_min INT NOT NULL, ADD pattern_max INT NOT NULL, ADD brindle_min INT NOT NULL, ADD brindle_max INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE breeds DROP rabicano_min, DROP rabicano_max, DROP roan_min, DROP roan_max, DROP sabino_min, DROP sabino_max, DROP white_min, DROP white_max, DROP tovero_min, DROP tovero_max, DROP overo_min, DROP overo_max, DROP splash_min, DROP splash_max, DROP leopard_min, DROP leopard_max, DROP pattern_min, DROP pattern_max, DROP brindle_min, DROP brindle_max');
    }
}
