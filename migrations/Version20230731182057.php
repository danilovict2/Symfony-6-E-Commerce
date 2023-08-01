<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20230731182057 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" ADD customer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D6499395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6499395C3F3 ON "user" (customer_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D6499395C3F3');
        $this->addSql('DROP INDEX UNIQ_8D93D6499395C3F3');
        $this->addSql('ALTER TABLE "user" DROP customer_id');
    }
}
