<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20230731173354 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP INDEX uniq_d34a04adc76f1f52');
        $this->addSql('CREATE INDEX IDX_D34A04ADC76F1F52 ON product (deleted_by_id)');
        $this->addSql('ALTER TABLE "user" ALTER is_verified SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX IDX_D34A04ADC76F1F52');
        $this->addSql('CREATE UNIQUE INDEX uniq_d34a04adc76f1f52 ON product (deleted_by_id)');
        $this->addSql('ALTER TABLE "user" ALTER is_verified DROP NOT NULL');
    }
}
