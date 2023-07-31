<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20230731180534 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE customer DROP CONSTRAINT fk_81398e09b03a8386');
        $this->addSql('ALTER TABLE customer DROP CONSTRAINT fk_81398e09896dbbde');
        $this->addSql('DROP INDEX uniq_81398e09896dbbde');
        $this->addSql('DROP INDEX uniq_81398e09b03a8386');
        $this->addSql('ALTER TABLE customer DROP created_by_id');
        $this->addSql('ALTER TABLE customer DROP updated_by_id');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT fk_f5299398b03a8386');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT fk_f5299398896dbbde');
        $this->addSql('DROP INDEX uniq_f5299398896dbbde');
        $this->addSql('DROP INDEX uniq_f5299398b03a8386');
        $this->addSql('ALTER TABLE "order" DROP created_by_id');
        $this->addSql('ALTER TABLE "order" DROP updated_by_id');
        $this->addSql('ALTER TABLE payment DROP CONSTRAINT fk_6d28840db03a8386');
        $this->addSql('ALTER TABLE payment DROP CONSTRAINT fk_6d28840d896dbbde');
        $this->addSql('DROP INDEX uniq_6d28840d896dbbde');
        $this->addSql('DROP INDEX uniq_6d28840db03a8386');
        $this->addSql('ALTER TABLE payment DROP created_by_id');
        $this->addSql('ALTER TABLE payment DROP updated_by_id');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT fk_d34a04adb03a8386');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT fk_d34a04ad896dbbde');
        $this->addSql('DROP INDEX uniq_d34a04ad896dbbde');
        $this->addSql('DROP INDEX uniq_d34a04adb03a8386');
        $this->addSql('ALTER TABLE product DROP created_by_id');
        $this->addSql('ALTER TABLE product DROP updated_by_id');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE customer ADD created_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE customer ADD updated_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT fk_81398e09b03a8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT fk_81398e09896dbbde FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_81398e09896dbbde ON customer (updated_by_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_81398e09b03a8386 ON customer (created_by_id)');
        $this->addSql('ALTER TABLE payment ADD created_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE payment ADD updated_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT fk_6d28840db03a8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT fk_6d28840d896dbbde FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_6d28840d896dbbde ON payment (updated_by_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_6d28840db03a8386 ON payment (created_by_id)');
        $this->addSql('ALTER TABLE "order" ADD created_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "order" ADD updated_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT fk_f5299398b03a8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT fk_f5299398896dbbde FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_f5299398896dbbde ON "order" (updated_by_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_f5299398b03a8386 ON "order" (created_by_id)');
        $this->addSql('ALTER TABLE product ADD created_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD updated_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT fk_d34a04adb03a8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT fk_d34a04ad896dbbde FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_d34a04ad896dbbde ON product (updated_by_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_d34a04adb03a8386 ON product (created_by_id)');
    }
}
