<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20230801162141 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE customer_adress DROP CONSTRAINT fk_ed291bef9395c3f3');
        $this->addSql('DROP INDEX uniq_ed291bef9395c3f3');
        $this->addSql('ALTER TABLE customer_adress DROP customer_id');
        $this->addSql('ALTER TABLE customer_adress DROP type');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE customer_adress ADD customer_id INT NOT NULL');
        $this->addSql('ALTER TABLE customer_adress ADD type VARCHAR(45) NOT NULL');
        $this->addSql('ALTER TABLE customer_adress ADD CONSTRAINT fk_ed291bef9395c3f3 FOREIGN KEY (customer_id) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_ed291bef9395c3f3 ON customer_adress (customer_id)');
    }
}
