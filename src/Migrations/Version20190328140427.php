<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190328140427 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE available ADD product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE available ADD CONSTRAINT FK_A58FA4854584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_A58FA4854584665A ON available (product_id)');
        $this->addSql('ALTER TABLE booking CHANGE status status ENUM(\'pending\', \'canceled\', \'confirmed\')');
        $this->addSql('ALTER TABLE easytext ADD locales_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE easytext ADD CONSTRAINT FK_B353B3ED164006B8 FOREIGN KEY (locales_id) REFERENCES locales (id)');
        $this->addSql('CREATE INDEX IDX_B353B3ED164006B8 ON easytext (locales_id)');
        $this->addSql('ALTER TABLE logs CHANGE status status ENUM(\'update\', \'create\', \'delete\')');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE available DROP FOREIGN KEY FK_A58FA4854584665A');
        $this->addSql('DROP INDEX IDX_A58FA4854584665A ON available');
        $this->addSql('ALTER TABLE available DROP product_id');
        $this->addSql('ALTER TABLE booking CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE easytext DROP FOREIGN KEY FK_B353B3ED164006B8');
        $this->addSql('DROP INDEX IDX_B353B3ED164006B8 ON easytext');
        $this->addSql('ALTER TABLE easytext DROP locales_id');
        $this->addSql('ALTER TABLE logs CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
    }
}
