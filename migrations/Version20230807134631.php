<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20230807134631 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "order" ADD created_by_id INT');
        $this->addSql('UPDATE "order" SET created_by_id = 4');
        $this->addSql('ALTER TABLE "order" ALTER COLUMN created_by_id SET NOT NULL');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F5299398B03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_F5299398B03A8386 ON "order" (created_by_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F5299398B03A8386');
        $this->addSql('DROP INDEX IDX_F5299398B03A8386');
        $this->addSql('ALTER TABLE "order" DROP created_by_id');
    }
}
