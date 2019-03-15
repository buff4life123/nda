<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190312211039 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE banner DROP FOREIGN KEY FK_6F9DB8E7C5106942');
        $this->addSql('DROP INDEX IDX_6F9DB8E7C5106942 ON banner');
        $this->addSql('ALTER TABLE banner DROP banner_translation_id');
        $this->addSql('ALTER TABLE banner_translation ADD banner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE banner_translation ADD CONSTRAINT FK_841ECF1C684EC833 FOREIGN KEY (banner_id) REFERENCES banner (id)');
        $this->addSql('CREATE INDEX IDX_841ECF1C684EC833 ON banner_translation (banner_id)');
        $this->addSql('ALTER TABLE booking CHANGE status status ENUM(\'pending\', \'canceled\', \'confirmed\')');
        $this->addSql('ALTER TABLE logs CHANGE status status ENUM(\'update\', \'create\', \'delete\')');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE banner ADD banner_translation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE banner ADD CONSTRAINT FK_6F9DB8E7C5106942 FOREIGN KEY (banner_translation_id) REFERENCES banner_translation (id)');
        $this->addSql('CREATE INDEX IDX_6F9DB8E7C5106942 ON banner (banner_translation_id)');
        $this->addSql('ALTER TABLE banner_translation DROP FOREIGN KEY FK_841ECF1C684EC833');
        $this->addSql('DROP INDEX IDX_841ECF1C684EC833 ON banner_translation');
        $this->addSql('ALTER TABLE banner_translation DROP banner_id');
        $this->addSql('ALTER TABLE booking CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE logs CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
    }
}
