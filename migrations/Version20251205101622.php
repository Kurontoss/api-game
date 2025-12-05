<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251205101622 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE knight ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE knight ADD CONSTRAINT FK_409A6B0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_409A6B0A76ED395 ON knight (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE knight DROP FOREIGN KEY FK_409A6B0A76ED395');
        $this->addSql('DROP INDEX IDX_409A6B0A76ED395 ON knight');
        $this->addSql('ALTER TABLE knight DROP user_id');
    }
}
