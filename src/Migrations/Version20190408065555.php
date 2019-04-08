<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190408065555 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE product_wp_translation (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, locales_id INT DEFAULT NULL, html LONGTEXT NOT NULL, INDEX IDX_268553144584665A (product_id), INDEX IDX_26855314164006B8 (locales_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_wp_translation ADD CONSTRAINT FK_268553144584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_wp_translation ADD CONSTRAINT FK_26855314164006B8 FOREIGN KEY (locales_id) REFERENCES locales (id)');
        $this->addSql('ALTER TABLE booking CHANGE status status ENUM(\'pending\', \'canceled\', \'confirmed\')');
        $this->addSql('ALTER TABLE logs CHANGE status status ENUM(\'update\', \'create\', \'delete\')');
        $this->addSql('ALTER TABLE price_translation DROP FOREIGN KEY FK_B3B00979D614C7E7');
        $this->addSql('DROP INDEX IDX_B3B00979D614C7E7 ON price_translation');
        $this->addSql('ALTER TABLE price_translation CHANGE price_id prices_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE price_translation ADD CONSTRAINT FK_B3B00979D9C9DE39 FOREIGN KEY (prices_id) REFERENCES price (id)');
        $this->addSql('CREATE INDEX IDX_B3B00979D9C9DE39 ON price_translation (prices_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE product_wp_translation');
        $this->addSql('ALTER TABLE booking CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE logs CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE price_translation DROP FOREIGN KEY FK_B3B00979D9C9DE39');
        $this->addSql('DROP INDEX IDX_B3B00979D9C9DE39 ON price_translation');
        $this->addSql('ALTER TABLE price_translation CHANGE prices_id price_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE price_translation ADD CONSTRAINT FK_B3B00979D614C7E7 FOREIGN KEY (price_id) REFERENCES price (id)');
        $this->addSql('CREATE INDEX IDX_B3B00979D614C7E7 ON price_translation (price_id)');
    }
}
