<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251210072544 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE enemy DROP FOREIGN KEY `FK_FB9F5AA94CDEDEC9`');
        $this->addSql('DROP INDEX IDX_FB9F5AA94CDEDEC9 ON enemy');
        $this->addSql('DROP TABLE drop_pool');
        $this->addSql('DROP TABLE drop_pool_item');
        $this->addSql('ALTER TABLE enemy CHANGE drop_pool_id loot_pool_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE enemy ADD CONSTRAINT FK_FB9F5AA9725C70F7 FOREIGN KEY (loot_pool_id) REFERENCES loot_pool (id)');
        $this->addSql('CREATE INDEX IDX_FB9F5AA9725C70F7 ON enemy (loot_pool_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE drop_pool (id INT AUTO_INCREMENT NOT NULL, chances LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, min_amounts LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, max_amounts LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE drop_pool_item (drop_pool_id INT NOT NULL, item_id INT NOT NULL, INDEX IDX_D25EC824CDEDEC9 (drop_pool_id), INDEX IDX_D25EC82126F525E (item_id), PRIMARY KEY (drop_pool_id, item_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE enemy DROP FOREIGN KEY FK_FB9F5AA9725C70F7');
        $this->addSql('DROP INDEX IDX_FB9F5AA9725C70F7 ON enemy');
        $this->addSql('ALTER TABLE enemy CHANGE loot_pool_id drop_pool_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE enemy ADD CONSTRAINT `FK_FB9F5AA94CDEDEC9` FOREIGN KEY (drop_pool_id) REFERENCES drop_pool (id)');
        $this->addSql('CREATE INDEX IDX_FB9F5AA94CDEDEC9 ON enemy (drop_pool_id)');
    }
}
