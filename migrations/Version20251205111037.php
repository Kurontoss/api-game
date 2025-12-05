<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251205111037 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dungeon ADD exp INT NOT NULL, CHANGE difficulty level INT NOT NULL');
        $this->addSql('ALTER TABLE knight ADD exp INT NOT NULL, ADD exp_to_next_level INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dungeon ADD difficulty INT NOT NULL, DROP level, DROP exp');
        $this->addSql('ALTER TABLE knight DROP exp, DROP exp_to_next_level');
    }
}
