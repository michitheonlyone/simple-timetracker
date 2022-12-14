<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221214145710 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE component (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE journal_entry (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, component_id INTEGER DEFAULT NULL, timestamp DATETIME NOT NULL, starting_time TIME DEFAULT NULL, ending_time TIME DEFAULT NULL, note VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_C8FAAE5AE2ABAFFF FOREIGN KEY (component_id) REFERENCES component (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_C8FAAE5AE2ABAFFF ON journal_entry (component_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE component');
        $this->addSql('DROP TABLE journal_entry');
    }
}
