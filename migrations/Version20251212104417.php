<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251212104417 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE loot_pool (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, chances JSON NOT NULL, min_amounts JSON NOT NULL, max_amounts JSON NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE loot_pool_item (loot_pool_id INT NOT NULL, item_id INT NOT NULL, INDEX IDX_7D8E4792725C70F7 (loot_pool_id), INDEX IDX_7D8E4792126F525E (item_id), PRIMARY KEY (loot_pool_id, item_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE loot_pool_item ADD CONSTRAINT FK_7D8E4792725C70F7 FOREIGN KEY (loot_pool_id) REFERENCES loot_pool (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE loot_pool_item ADD CONSTRAINT FK_7D8E4792126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE drop_pool_item DROP FOREIGN KEY `FK_D25EC82126F525E`');
        $this->addSql('ALTER TABLE drop_pool_item DROP FOREIGN KEY `FK_D25EC824CDEDEC9`');
        $this->addSql('DROP TABLE drop_pool');
        $this->addSql('DROP TABLE drop_pool_item');
        $this->addSql('ALTER TABLE dungeon CHANGE name name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE enemy CHANGE name name VARCHAR(255) NOT NULL, CHANGE drop_pool_id loot_pool_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE enemy ADD CONSTRAINT FK_FB9F5AA9725C70F7 FOREIGN KEY (loot_pool_id) REFERENCES loot_pool (id)');
        $this->addSql('CREATE INDEX IDX_FB9F5AA9725C70F7 ON enemy (loot_pool_id)');
        $this->addSql('ALTER TABLE item CHANGE name name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE knight ADD max_hp INT NOT NULL, CHANGE name name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE name name VARCHAR(255) NOT NULL, CHANGE password password VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE drop_pool (id INT AUTO_INCREMENT NOT NULL, chances LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, min_amounts LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, max_amounts LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE drop_pool_item (drop_pool_id INT NOT NULL, item_id INT NOT NULL, INDEX IDX_D25EC824CDEDEC9 (drop_pool_id), INDEX IDX_D25EC82126F525E (item_id), PRIMARY KEY (drop_pool_id, item_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE drop_pool_item ADD CONSTRAINT `FK_D25EC82126F525E` FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE drop_pool_item ADD CONSTRAINT `FK_D25EC824CDEDEC9` FOREIGN KEY (drop_pool_id) REFERENCES drop_pool (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE loot_pool_item DROP FOREIGN KEY FK_7D8E4792725C70F7');
        $this->addSql('ALTER TABLE loot_pool_item DROP FOREIGN KEY FK_7D8E4792126F525E');
        $this->addSql('DROP TABLE loot_pool');
        $this->addSql('DROP TABLE loot_pool_item');
        $this->addSql('ALTER TABLE dungeon CHANGE name name VARCHAR(256) NOT NULL');
        $this->addSql('ALTER TABLE enemy DROP FOREIGN KEY FK_FB9F5AA9725C70F7');
        $this->addSql('DROP INDEX IDX_FB9F5AA9725C70F7 ON enemy');
        $this->addSql('ALTER TABLE enemy CHANGE name name VARCHAR(256) NOT NULL, CHANGE loot_pool_id drop_pool_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE item CHANGE name name VARCHAR(256) NOT NULL');
        $this->addSql('ALTER TABLE knight DROP max_hp, CHANGE name name VARCHAR(256) NOT NULL');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON user');
        $this->addSql('ALTER TABLE user CHANGE name name VARCHAR(256) NOT NULL, CHANGE password password VARCHAR(256) NOT NULL');
    }
}
