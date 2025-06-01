<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250601182332 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE ingredient ADD stock_id INT NOT NULL, ADD type VARCHAR(45) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ingredient ADD CONSTRAINT FK_6BAF7870DCD6110 FOREIGN KEY (stock_id) REFERENCES ingredient_stock (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_6BAF7870DCD6110 ON ingredient (stock_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ingredient_stock DROP FOREIGN KEY FK_520431A1933FE08C
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_520431A1933FE08C ON ingredient_stock
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ingredient_stock DROP ingredient_id
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE ingredient DROP FOREIGN KEY FK_6BAF7870DCD6110
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_6BAF7870DCD6110 ON ingredient
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ingredient DROP stock_id, DROP type
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ingredient_stock ADD ingredient_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ingredient_stock ADD CONSTRAINT FK_520431A1933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_520431A1933FE08C ON ingredient_stock (ingredient_id)
        SQL);
    }
}
