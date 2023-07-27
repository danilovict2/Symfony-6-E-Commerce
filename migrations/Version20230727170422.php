<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20230727170422 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE cart_item_id_seq CASCADE');
        $this->addSql('ALTER TABLE cart_item DROP CONSTRAINT fk_f0fe252761220ea6');
        $this->addSql('ALTER TABLE cart_item DROP CONSTRAINT fk_f0fe25274584665a');
        $this->addSql('DROP TABLE cart_item');
        $this->addSql('ALTER TABLE "user" ALTER is_verified SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE cart_item_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE cart_item (id INT NOT NULL, creator_id INT NOT NULL, product_id INT NOT NULL, quantity INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_f0fe25274584665a ON cart_item (product_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_f0fe252761220ea6 ON cart_item (creator_id)');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT fk_f0fe252761220ea6 FOREIGN KEY (creator_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT fk_f0fe25274584665a FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ALTER is_verified DROP NOT NULL');
    }
}
