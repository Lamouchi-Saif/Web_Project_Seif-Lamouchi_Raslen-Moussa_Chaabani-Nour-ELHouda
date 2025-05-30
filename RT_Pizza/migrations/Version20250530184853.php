<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250530184853 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE ingredient_stock DROP INDEX IDX_520431A1933FE08C, ADD UNIQUE INDEX UNIQ_520431A1933FE08C (ingredient_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ingredient_stock CHANGE ingredient_id ingredient_id INT NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE ingredient_stock DROP INDEX UNIQ_520431A1933FE08C, ADD INDEX IDX_520431A1933FE08C (ingredient_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ingredient_stock CHANGE ingredient_id ingredient_id INT DEFAULT NULL
        SQL);
    }
}
