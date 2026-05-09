<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260509010000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create todo_entry table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE todo_entry (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, created_at DATETIME NOT NULL, customer VARCHAR(20) DEFAULT NULL, note VARCHAR(255) DEFAULT NULL, archived BOOLEAN NOT NULL)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE todo_entry');
    }
}
