<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251208081844 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE enemy ADD dungeon_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE enemy ADD CONSTRAINT FK_FB9F5AA9B606863 FOREIGN KEY (dungeon_id) REFERENCES dungeon (id)');
        $this->addSql('CREATE INDEX IDX_FB9F5AA9B606863 ON enemy (dungeon_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE enemy DROP FOREIGN KEY FK_FB9F5AA9B606863');
        $this->addSql('DROP INDEX IDX_FB9F5AA9B606863 ON enemy');
        $this->addSql('ALTER TABLE enemy DROP dungeon_id');
    }
}
