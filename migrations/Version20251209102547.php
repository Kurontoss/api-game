<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251209102547 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE drop_pool (id INT AUTO_INCREMENT NOT NULL, chances LONGTEXT NOT NULL, min_amounts LONGTEXT NOT NULL, max_amounts LONGTEXT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE drop_pool_item (drop_pool_id INT NOT NULL, item_id INT NOT NULL, INDEX IDX_D25EC824CDEDEC9 (drop_pool_id), INDEX IDX_D25EC82126F525E (item_id), PRIMARY KEY (drop_pool_id, item_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE inventory_item (id INT AUTO_INCREMENT NOT NULL, amount INT NOT NULL, item_id INT NOT NULL, knight_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_55BDEA30126F525E (item_id), INDEX IDX_55BDEA30229E98EF (knight_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(256) NOT NULL, value INT NOT NULL, type VARCHAR(255) NOT NULL, hp_regen INT DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE drop_pool_item ADD CONSTRAINT FK_D25EC824CDEDEC9 FOREIGN KEY (drop_pool_id) REFERENCES drop_pool (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE drop_pool_item ADD CONSTRAINT FK_D25EC82126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inventory_item ADD CONSTRAINT FK_55BDEA30126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE inventory_item ADD CONSTRAINT FK_55BDEA30229E98EF FOREIGN KEY (knight_id) REFERENCES knight (id)');
        $this->addSql('ALTER TABLE enemy ADD drop_pool_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE enemy ADD CONSTRAINT FK_FB9F5AA94CDEDEC9 FOREIGN KEY (drop_pool_id) REFERENCES drop_pool (id)');
        $this->addSql('CREATE INDEX IDX_FB9F5AA94CDEDEC9 ON enemy (drop_pool_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE drop_pool_item DROP FOREIGN KEY FK_D25EC824CDEDEC9');
        $this->addSql('ALTER TABLE drop_pool_item DROP FOREIGN KEY FK_D25EC82126F525E');
        $this->addSql('ALTER TABLE inventory_item DROP FOREIGN KEY FK_55BDEA30126F525E');
        $this->addSql('ALTER TABLE inventory_item DROP FOREIGN KEY FK_55BDEA30229E98EF');
        $this->addSql('DROP TABLE drop_pool');
        $this->addSql('DROP TABLE drop_pool_item');
        $this->addSql('DROP TABLE inventory_item');
        $this->addSql('DROP TABLE item');
        $this->addSql('ALTER TABLE enemy DROP FOREIGN KEY FK_FB9F5AA94CDEDEC9');
        $this->addSql('DROP INDEX IDX_FB9F5AA94CDEDEC9 ON enemy');
        $this->addSql('ALTER TABLE enemy DROP drop_pool_id');
    }
}
