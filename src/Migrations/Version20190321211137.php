<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190321211137 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE price (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, is_active TINYINT(1) DEFAULT \'0\' NOT NULL, is_child TINYINT(1) DEFAULT \'0\' NOT NULL, amount INT(11) UNSIGNED COMMENT "(DC2Type:money)" NOT NULL COMMENT \'(DC2Type:money)\', INDEX IDX_CAC822D94584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE price_translation (id INT AUTO_INCREMENT NOT NULL, locales_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, INDEX IDX_B3B00979164006B8 (locales_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE price ADD CONSTRAINT FK_CAC822D94584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE price_translation ADD CONSTRAINT FK_B3B00979164006B8 FOREIGN KEY (locales_id) REFERENCES locales (id)');
        $this->addSql('ALTER TABLE available DROP FOREIGN KEY FK_A58FA4854584665A');
        $this->addSql('DROP INDEX IDX_A58FA4854584665A ON available');
        $this->addSql('ALTER TABLE available DROP product_id');
        $this->addSql('ALTER TABLE booking CHANGE status status ENUM(\'pending\', \'canceled\', \'confirmed\')');
        $this->addSql('ALTER TABLE logs CHANGE status status ENUM(\'update\', \'create\', \'delete\')');
        $this->addSql('ALTER TABLE product DROP name_pt, DROP name_en, DROP children_price, DROP adult_price');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE price');
        $this->addSql('DROP TABLE price_translation');
        $this->addSql('ALTER TABLE available ADD product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE available ADD CONSTRAINT FK_A58FA4854584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_A58FA4854584665A ON available (product_id)');
        $this->addSql('ALTER TABLE booking CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE logs CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE product ADD name_pt VARCHAR(50) NOT NULL COLLATE utf8mb4_unicode_ci, ADD name_en VARCHAR(50) NOT NULL COLLATE utf8mb4_unicode_ci, ADD children_price INT(11) UNSIGNED COMMENT "(DC2Type:money)" NOT NULL COMMENT \'(DC2Type:money)\', ADD adult_price INT(11) UNSIGNED COMMENT "(DC2Type:money)" NOT NULL COMMENT \'(DC2Type:money)\'');
    }
}
