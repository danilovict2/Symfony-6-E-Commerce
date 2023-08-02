<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20230802141056 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE customer DROP CONSTRAINT fk_81398e09c273a89b');
        $this->addSql('ALTER TABLE customer DROP CONSTRAINT fk_81398e0930959bf2');
        $this->addSql('DROP SEQUENCE customer_adress_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE customer_address_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE customer_address (id INT NOT NULL, country_id INT NOT NULL, address1 VARCHAR(255) NOT NULL, address2 VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, state VARCHAR(45) DEFAULT NULL, country_code VARCHAR(3) NOT NULL, zipcode VARCHAR(45) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1193CB3FF92F3E70 ON customer_address (country_id)');
        $this->addSql('ALTER TABLE customer_address ADD CONSTRAINT FK_1193CB3FF92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE customer_adress DROP CONSTRAINT fk_ed291beff92f3e70');
        $this->addSql('DROP TABLE customer_adress');
        $this->addSql('DROP INDEX uniq_81398e0930959bf2');
        $this->addSql('DROP INDEX uniq_81398e09c273a89b');
        $this->addSql('ALTER TABLE customer ADD shipping_address_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE customer ADD billing_address_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE customer DROP shipping_adress_id');
        $this->addSql('ALTER TABLE customer DROP billing_adress_id');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E094D4CFF2B FOREIGN KEY (shipping_address_id) REFERENCES customer_address (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E0979D0C0E4 FOREIGN KEY (billing_address_id) REFERENCES customer_address (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_81398E094D4CFF2B ON customer (shipping_address_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_81398E0979D0C0E4 ON customer (billing_address_id)');
        $this->addSql('ALTER TABLE order_detail ADD address1 VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE order_detail ADD address2 VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE order_detail DROP adress1');
        $this->addSql('ALTER TABLE order_detail DROP adress2');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE customer DROP CONSTRAINT FK_81398E094D4CFF2B');
        $this->addSql('ALTER TABLE customer DROP CONSTRAINT FK_81398E0979D0C0E4');
        $this->addSql('DROP SEQUENCE customer_address_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE customer_adress_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE customer_adress (id INT NOT NULL, country_id INT NOT NULL, adress1 VARCHAR(255) NOT NULL, adress2 VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, state VARCHAR(45) DEFAULT NULL, country_code VARCHAR(3) NOT NULL, zipcode VARCHAR(45) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_ed291beff92f3e70 ON customer_adress (country_id)');
        $this->addSql('ALTER TABLE customer_adress ADD CONSTRAINT fk_ed291beff92f3e70 FOREIGN KEY (country_id) REFERENCES country (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE customer_address DROP CONSTRAINT FK_1193CB3FF92F3E70');
        $this->addSql('DROP TABLE customer_address');
        $this->addSql('ALTER TABLE order_detail ADD adress1 VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE order_detail ADD adress2 VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE order_detail DROP address1');
        $this->addSql('ALTER TABLE order_detail DROP address2');
        $this->addSql('DROP INDEX UNIQ_81398E094D4CFF2B');
        $this->addSql('DROP INDEX UNIQ_81398E0979D0C0E4');
        $this->addSql('ALTER TABLE customer ADD shipping_adress_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE customer ADD billing_adress_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE customer DROP shipping_address_id');
        $this->addSql('ALTER TABLE customer DROP billing_address_id');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT fk_81398e09c273a89b FOREIGN KEY (shipping_adress_id) REFERENCES customer_adress (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT fk_81398e0930959bf2 FOREIGN KEY (billing_adress_id) REFERENCES customer_adress (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_81398e0930959bf2 ON customer (billing_adress_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_81398e09c273a89b ON customer (shipping_adress_id)');
    }
}
