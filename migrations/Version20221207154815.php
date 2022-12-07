<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221207154815 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE journal_entry ADD COLUMN optional_start_time TIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__journal_entry AS SELECT id, component_id, timestamp, reference, note FROM journal_entry');
        $this->addSql('DROP TABLE journal_entry');
        $this->addSql('CREATE TABLE journal_entry (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, component_id INTEGER DEFAULT NULL, timestamp DATETIME NOT NULL, reference VARCHAR(24) DEFAULT NULL, note VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_C8FAAE5AE2ABAFFF FOREIGN KEY (component_id) REFERENCES component (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO journal_entry (id, component_id, timestamp, reference, note) SELECT id, component_id, timestamp, reference, note FROM __temp__journal_entry');
        $this->addSql('DROP TABLE __temp__journal_entry');
        $this->addSql('CREATE INDEX IDX_C8FAAE5AE2ABAFFF ON journal_entry (component_id)');
    }
}
