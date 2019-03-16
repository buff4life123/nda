<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190316142405 extends AbstractMigration
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
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93164006B8');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A938560DE01');
        $this->addSql('DROP INDEX IDX_7D053A938560DE01 ON menu');
        $this->addSql('DROP INDEX IDX_7D053A93164006B8 ON menu');
        $this->addSql('ALTER TABLE menu DROP locales_id, DROP menu_translation_id, CHANGE link_active link_active TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE menu_translation ADD menu_id INT DEFAULT NULL, ADD locales_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE menu_translation ADD CONSTRAINT FK_DC955B23CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE menu_translation ADD CONSTRAINT FK_DC955B23164006B8 FOREIGN KEY (locales_id) REFERENCES locales (id)');
        $this->addSql('CREATE INDEX IDX_DC955B23CCD7E912 ON menu_translation (menu_id)');
        $this->addSql('CREATE INDEX IDX_DC955B23164006B8 ON menu_translation (locales_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE booking CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE logs CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE menu ADD locales_id INT DEFAULT NULL, ADD menu_translation_id INT DEFAULT NULL, CHANGE link_active link_active TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93164006B8 FOREIGN KEY (locales_id) REFERENCES locales (id)');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A938560DE01 FOREIGN KEY (menu_translation_id) REFERENCES menu_translation (id)');
        $this->addSql('CREATE INDEX IDX_7D053A938560DE01 ON menu (menu_translation_id)');
        $this->addSql('CREATE INDEX IDX_7D053A93164006B8 ON menu (locales_id)');
        $this->addSql('ALTER TABLE menu_translation DROP FOREIGN KEY FK_DC955B23CCD7E912');
        $this->addSql('ALTER TABLE menu_translation DROP FOREIGN KEY FK_DC955B23164006B8');
        $this->addSql('DROP INDEX IDX_DC955B23CCD7E912 ON menu_translation');
        $this->addSql('DROP INDEX IDX_DC955B23164006B8 ON menu_translation');
        $this->addSql('ALTER TABLE menu_translation DROP menu_id, DROP locales_id');
    }
}
