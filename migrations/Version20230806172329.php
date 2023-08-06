<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20230806172329 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP INDEX uniq_52ea1f094584665a');
        $this->addSql('CREATE INDEX IDX_52EA1F094584665A ON order_item (product_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX IDX_52EA1F094584665A');
        $this->addSql('CREATE UNIQUE INDEX uniq_52ea1f094584665a ON order_item (product_id)');
    }
}
