<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251209105134 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE drop_pool_item (drop_pool_id INT NOT NULL, item_id INT NOT NULL, INDEX IDX_D25EC824CDEDEC9 (drop_pool_id), INDEX IDX_D25EC82126F525E (item_id), PRIMARY KEY (drop_pool_id, item_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE drop_pool_item ADD CONSTRAINT FK_D25EC824CDEDEC9 FOREIGN KEY (drop_pool_id) REFERENCES drop_pool (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE drop_pool_item ADD CONSTRAINT FK_D25EC82126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE drop_pool_item DROP FOREIGN KEY FK_D25EC824CDEDEC9');
        $this->addSql('ALTER TABLE drop_pool_item DROP FOREIGN KEY FK_D25EC82126F525E');
        $this->addSql('DROP TABLE drop_pool_item');
    }
}
