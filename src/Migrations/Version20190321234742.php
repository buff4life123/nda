<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190321234742 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE booking CHANGE status status ENUM(\'pending\', \'canceled\', \'confirmed\')');
        $this->addSql('ALTER TABLE logs CHANGE status status ENUM(\'update\', \'create\', \'delete\')');
        $this->addSql('ALTER TABLE product_description_translation ADD locales_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product_description_translation ADD CONSTRAINT FK_119EDAE4164006B8 FOREIGN KEY (locales_id) REFERENCES locales (id)');
        $this->addSql('CREATE INDEX IDX_119EDAE4164006B8 ON product_description_translation (locales_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE booking CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE logs CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE product_description_translation DROP FOREIGN KEY FK_119EDAE4164006B8');
        $this->addSql('DROP INDEX IDX_119EDAE4164006B8 ON product_description_translation');
        $this->addSql('ALTER TABLE product_description_translation DROP locales_id');
    }
}
