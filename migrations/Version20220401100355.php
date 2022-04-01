<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220401100355 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE club ADD pelouse VARCHAR(255) NOT NULL, ADD places INT NOT NULL, ADD couberture VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE club ADD CONSTRAINT FK_B8EE3872F98F144A FOREIGN KEY (logo_id) REFERENCES logo (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B8EE3872F98F144A ON club (logo_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE club DROP FOREIGN KEY FK_B8EE3872F98F144A');
        $this->addSql('DROP INDEX UNIQ_B8EE3872F98F144A ON club');
        $this->addSql('ALTER TABLE club DROP pelouse, DROP places, DROP couberture');
    }
}
