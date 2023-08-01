<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20230801161807 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE customer ADD shipping_adress_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE customer ADD billing_adress_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E09C273A89B FOREIGN KEY (shipping_adress_id) REFERENCES customer_adress (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E0930959BF2 FOREIGN KEY (billing_adress_id) REFERENCES customer_adress (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_81398E09C273A89B ON customer (shipping_adress_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_81398E0930959BF2 ON customer (billing_adress_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE customer DROP CONSTRAINT FK_81398E09C273A89B');
        $this->addSql('ALTER TABLE customer DROP CONSTRAINT FK_81398E0930959BF2');
        $this->addSql('DROP INDEX UNIQ_81398E09C273A89B');
        $this->addSql('DROP INDEX UNIQ_81398E0930959BF2');
        $this->addSql('ALTER TABLE customer DROP shipping_adress_id');
        $this->addSql('ALTER TABLE customer DROP billing_adress_id');
    }
}
