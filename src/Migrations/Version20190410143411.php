<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190410143411 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE about_us (id INT AUTO_INCREMENT NOT NULL, locales_id INT DEFAULT NULL, rgpd_html LONGTEXT NOT NULL, name VARCHAR(50) NOT NULL, INDEX IDX_B52303C3164006B8 (locales_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE amount (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, is_active TINYINT(1) DEFAULT \'0\' NOT NULL, is_child TINYINT(1) DEFAULT \'0\' NOT NULL, amount INT(11) UNSIGNED COMMENT "(DC2Type:money)" NOT NULL COMMENT \'(DC2Type:money)\', INDEX IDX_8EA170424584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE amount_translation (id INT AUTO_INCREMENT NOT NULL, amount_id INT DEFAULT NULL, locales_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, INDEX IDX_20A3363F9BB17698 (amount_id), INDEX IDX_20A3363F164006B8 (locales_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE available (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, datetimestart DATETIME NOT NULL, stock INT DEFAULT NULL, lotation INT DEFAULT NULL, datetimeend DATETIME NOT NULL, INDEX IDX_A58FA4854584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE banner (id INT AUTO_INCREMENT NOT NULL, image VARCHAR(255) DEFAULT NULL, is_active TINYINT(1) DEFAULT \'0\' NOT NULL, order_by INT DEFAULT NULL, text_active TINYINT(1) DEFAULT \'0\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE banner_translation (id INT AUTO_INCREMENT NOT NULL, banner_id INT DEFAULT NULL, locales_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, INDEX IDX_841ECF1C684EC833 (banner_id), INDEX IDX_841ECF1C164006B8 (locales_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE booking (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, available_id INT DEFAULT NULL, adult INT DEFAULT NULL, children INT DEFAULT NULL, baby INT DEFAULT NULL, date_event DATE NOT NULL, time_event TIME NOT NULL, posted_at DATE NOT NULL, notes LONGTEXT DEFAULT NULL, status ENUM(\'pending\', \'canceled\', \'confirmed\'), amount INT(11) UNSIGNED COMMENT "(DC2Type:money)" NOT NULL COMMENT \'(DC2Type:money)\', INDEX IDX_E00CEDDE19EB6921 (client_id), INDEX IDX_E00CEDDE36D3FBA2 (available_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, is_active TINYINT(1) DEFAULT \'0\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_translation (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, locales_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, INDEX IDX_3F2070412469DE2 (category_id), INDEX IDX_3F20704164006B8 (locales_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, locale_id INT DEFAULT NULL, email VARCHAR(100) NOT NULL, password VARCHAR(64) NOT NULL, username VARCHAR(100) NOT NULL, address VARCHAR(100) NOT NULL, telephone VARCHAR(100) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', rgpd TINYINT(1) NOT NULL, cvv LONGTEXT DEFAULT NULL, card_name LONGTEXT DEFAULT NULL, card_nr LONGTEXT DEFAULT NULL, card_date LONGTEXT DEFAULT NULL, INDEX IDX_C7440455E559DFD1 (locale_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, country_id INT DEFAULT NULL, currency_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, address VARCHAR(50) NOT NULL, p_code VARCHAR(10) NOT NULL, city VARCHAR(10) NOT NULL, email VARCHAR(30) NOT NULL, email_pass VARCHAR(128) NOT NULL, email_port INT NOT NULL, email_smtp VARCHAR(24) NOT NULL, email_certificade VARCHAR(4) NOT NULL, telephone VARCHAR(20) DEFAULT NULL, meta_keywords LONGTEXT DEFAULT NULL, meta_description LONGTEXT DEFAULT NULL, fiscal_number VARCHAR(50) NOT NULL, coords_google_maps VARCHAR(50) NOT NULL, google_maps_api_key VARCHAR(50) NOT NULL, logo VARCHAR(255) DEFAULT NULL, link_my_domain VARCHAR(255) DEFAULT NULL, link_facebook VARCHAR(255) DEFAULT NULL, link_twitter VARCHAR(255) DEFAULT NULL, link_instagram VARCHAR(255) DEFAULT NULL, link_linken VARCHAR(255) DEFAULT NULL, link_pinterest VARCHAR(255) DEFAULT NULL, link_youtube VARCHAR(255) DEFAULT NULL, link_behance VARCHAR(255) DEFAULT NULL, link_snapchat VARCHAR(255) DEFAULT NULL, link_facebook_active TINYINT(1) DEFAULT \'0\' NOT NULL, link_twitter_active TINYINT(1) DEFAULT \'0\' NOT NULL, link_instagram_active TINYINT(1) DEFAULT \'0\' NOT NULL, link_linken_active TINYINT(1) DEFAULT \'0\' NOT NULL, link_pinterest_active TINYINT(1) DEFAULT \'0\' NOT NULL, link_youtube_active TINYINT(1) DEFAULT NULL, link_behance_active TINYINT(1) DEFAULT NULL, link_snapchat_active TINYINT(1) DEFAULT NULL, INDEX IDX_4FBF094FF92F3E70 (country_id), INDEX IDX_4FBF094F38248176 (currency_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE countries (id INT AUTO_INCREMENT NOT NULL, iso VARCHAR(2) NOT NULL, name VARCHAR(50) NOT NULL, nicename VARCHAR(50) NOT NULL, iso3 VARCHAR(3) NOT NULL, numcode INT NOT NULL, phonecode INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE currency (id INT AUTO_INCREMENT NOT NULL, entity VARCHAR(24) NOT NULL, currency VARCHAR(24) NOT NULL, alphabetic_code VARCHAR(3) NOT NULL, `numeric` VARCHAR(3) NOT NULL, minor INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE easytext (id INT AUTO_INCREMENT NOT NULL, locales_id INT DEFAULT NULL, easy_text LONGTEXT NOT NULL, easy_text_html LONGTEXT NOT NULL, name VARCHAR(50) NOT NULL, INDEX IDX_B353B3ED164006B8 (locales_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, event VARCHAR(150) NOT NULL, INDEX IDX_3BAE0AA74584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gallery (id INT AUTO_INCREMENT NOT NULL, order_by INT DEFAULT NULL, is_active TINYINT(1) DEFAULT \'0\' NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gallery_translation (id INT AUTO_INCREMENT NOT NULL, gallery_id INT DEFAULT NULL, locales_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, INDEX IDX_5D650CAB4E7AF8F (gallery_id), INDEX IDX_5D650CAB164006B8 (locales_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE locales (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(10) NOT NULL, filename VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE logs (id INT AUTO_INCREMENT NOT NULL, datetime DATETIME NOT NULL, log LONGTEXT DEFAULT NULL, status ENUM(\'update\', \'create\', \'delete\'), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu (id INT AUTO_INCREMENT NOT NULL, order_by INT DEFAULT NULL, is_active TINYINT(1) DEFAULT \'0\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_translation (id INT AUTO_INCREMENT NOT NULL, menu_id INT DEFAULT NULL, locales_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, INDEX IDX_DC955B23CCD7E912 (menu_id), INDEX IDX_DC955B23164006B8 (locales_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE price (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, is_active TINYINT(1) DEFAULT \'0\' NOT NULL, is_child TINYINT(1) DEFAULT \'0\' NOT NULL, amount INT(11) UNSIGNED COMMENT "(DC2Type:money)" NOT NULL COMMENT \'(DC2Type:money)\', INDEX IDX_CAC822D94584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE price_translation (id INT AUTO_INCREMENT NOT NULL, price_id INT DEFAULT NULL, locales_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, INDEX IDX_B3B00979D614C7E7 (price_id), INDEX IDX_B3B00979164006B8 (locales_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, is_active TINYINT(1) DEFAULT \'0\' NOT NULL, image VARCHAR(255) NOT NULL, availability INT NOT NULL, highlight TINYINT(1) DEFAULT \'0\' NOT NULL, warranty_payment TINYINT(1) DEFAULT \'0\' NOT NULL, duration VARCHAR(5) DEFAULT \'00:00\' NOT NULL, order_by INT DEFAULT NULL, INDEX IDX_D34A04AD12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_description_translation (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, locales_id INT DEFAULT NULL, html LONGTEXT NOT NULL, name VARCHAR(50) NOT NULL, INDEX IDX_119EDAE44584665A (product_id), INDEX IDX_119EDAE4164006B8 (locales_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_wp_translation (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, locales_id INT DEFAULT NULL, html LONGTEXT NOT NULL, INDEX IDX_268553144584665A (product_id), INDEX IDX_26855314164006B8 (locales_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rgpd (id INT AUTO_INCREMENT NOT NULL, locales_id INT DEFAULT NULL, rgpd_html LONGTEXT NOT NULL, name VARCHAR(50) NOT NULL, INDEX IDX_C80AB619164006B8 (locales_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE terms_conditions (id INT AUTO_INCREMENT NOT NULL, locales_id INT DEFAULT NULL, terms_html LONGTEXT NOT NULL, name VARCHAR(50) NOT NULL, INDEX IDX_7BF59952164006B8 (locales_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(100) NOT NULL, username VARCHAR(100) NOT NULL, password VARCHAR(64) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', status TINYINT(1) DEFAULT \'0\' NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE warning (id INT AUTO_INCREMENT NOT NULL, is_active TINYINT(1) DEFAULT \'0\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE warning_translation (id INT AUTO_INCREMENT NOT NULL, warning_id INT DEFAULT NULL, locales_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, INDEX IDX_D8A1DDE6BFF38603 (warning_id), INDEX IDX_D8A1DDE6164006B8 (locales_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE about_us ADD CONSTRAINT FK_B52303C3164006B8 FOREIGN KEY (locales_id) REFERENCES locales (id)');
        $this->addSql('ALTER TABLE amount ADD CONSTRAINT FK_8EA170424584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE amount_translation ADD CONSTRAINT FK_20A3363F9BB17698 FOREIGN KEY (amount_id) REFERENCES amount (id)');
        $this->addSql('ALTER TABLE amount_translation ADD CONSTRAINT FK_20A3363F164006B8 FOREIGN KEY (locales_id) REFERENCES locales (id)');
        $this->addSql('ALTER TABLE available ADD CONSTRAINT FK_A58FA4854584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE banner_translation ADD CONSTRAINT FK_841ECF1C684EC833 FOREIGN KEY (banner_id) REFERENCES banner (id)');
        $this->addSql('ALTER TABLE banner_translation ADD CONSTRAINT FK_841ECF1C164006B8 FOREIGN KEY (locales_id) REFERENCES locales (id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE36D3FBA2 FOREIGN KEY (available_id) REFERENCES available (id)');
        $this->addSql('ALTER TABLE category_translation ADD CONSTRAINT FK_3F2070412469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE category_translation ADD CONSTRAINT FK_3F20704164006B8 FOREIGN KEY (locales_id) REFERENCES locales (id)');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455E559DFD1 FOREIGN KEY (locale_id) REFERENCES locales (id)');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094FF92F3E70 FOREIGN KEY (country_id) REFERENCES countries (id)');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094F38248176 FOREIGN KEY (currency_id) REFERENCES currency (id)');
        $this->addSql('ALTER TABLE easytext ADD CONSTRAINT FK_B353B3ED164006B8 FOREIGN KEY (locales_id) REFERENCES locales (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA74584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE gallery_translation ADD CONSTRAINT FK_5D650CAB4E7AF8F FOREIGN KEY (gallery_id) REFERENCES gallery (id)');
        $this->addSql('ALTER TABLE gallery_translation ADD CONSTRAINT FK_5D650CAB164006B8 FOREIGN KEY (locales_id) REFERENCES locales (id)');
        $this->addSql('ALTER TABLE menu_translation ADD CONSTRAINT FK_DC955B23CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE menu_translation ADD CONSTRAINT FK_DC955B23164006B8 FOREIGN KEY (locales_id) REFERENCES locales (id)');
        $this->addSql('ALTER TABLE price ADD CONSTRAINT FK_CAC822D94584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE price_translation ADD CONSTRAINT FK_B3B00979D614C7E7 FOREIGN KEY (price_id) REFERENCES price (id)');
        $this->addSql('ALTER TABLE price_translation ADD CONSTRAINT FK_B3B00979164006B8 FOREIGN KEY (locales_id) REFERENCES locales (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE product_description_translation ADD CONSTRAINT FK_119EDAE44584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_description_translation ADD CONSTRAINT FK_119EDAE4164006B8 FOREIGN KEY (locales_id) REFERENCES locales (id)');
        $this->addSql('ALTER TABLE product_wp_translation ADD CONSTRAINT FK_268553144584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_wp_translation ADD CONSTRAINT FK_26855314164006B8 FOREIGN KEY (locales_id) REFERENCES locales (id)');
        $this->addSql('ALTER TABLE rgpd ADD CONSTRAINT FK_C80AB619164006B8 FOREIGN KEY (locales_id) REFERENCES locales (id)');
        $this->addSql('ALTER TABLE terms_conditions ADD CONSTRAINT FK_7BF59952164006B8 FOREIGN KEY (locales_id) REFERENCES locales (id)');
        $this->addSql('ALTER TABLE warning_translation ADD CONSTRAINT FK_D8A1DDE6BFF38603 FOREIGN KEY (warning_id) REFERENCES warning (id)');
        $this->addSql('ALTER TABLE warning_translation ADD CONSTRAINT FK_D8A1DDE6164006B8 FOREIGN KEY (locales_id) REFERENCES locales (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE amount_translation DROP FOREIGN KEY FK_20A3363F9BB17698');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE36D3FBA2');
        $this->addSql('ALTER TABLE banner_translation DROP FOREIGN KEY FK_841ECF1C684EC833');
        $this->addSql('ALTER TABLE category_translation DROP FOREIGN KEY FK_3F2070412469DE2');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE19EB6921');
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094FF92F3E70');
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094F38248176');
        $this->addSql('ALTER TABLE gallery_translation DROP FOREIGN KEY FK_5D650CAB4E7AF8F');
        $this->addSql('ALTER TABLE about_us DROP FOREIGN KEY FK_B52303C3164006B8');
        $this->addSql('ALTER TABLE amount_translation DROP FOREIGN KEY FK_20A3363F164006B8');
        $this->addSql('ALTER TABLE banner_translation DROP FOREIGN KEY FK_841ECF1C164006B8');
        $this->addSql('ALTER TABLE category_translation DROP FOREIGN KEY FK_3F20704164006B8');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455E559DFD1');
        $this->addSql('ALTER TABLE easytext DROP FOREIGN KEY FK_B353B3ED164006B8');
        $this->addSql('ALTER TABLE gallery_translation DROP FOREIGN KEY FK_5D650CAB164006B8');
        $this->addSql('ALTER TABLE menu_translation DROP FOREIGN KEY FK_DC955B23164006B8');
        $this->addSql('ALTER TABLE price_translation DROP FOREIGN KEY FK_B3B00979164006B8');
        $this->addSql('ALTER TABLE product_description_translation DROP FOREIGN KEY FK_119EDAE4164006B8');
        $this->addSql('ALTER TABLE product_wp_translation DROP FOREIGN KEY FK_26855314164006B8');
        $this->addSql('ALTER TABLE rgpd DROP FOREIGN KEY FK_C80AB619164006B8');
        $this->addSql('ALTER TABLE terms_conditions DROP FOREIGN KEY FK_7BF59952164006B8');
        $this->addSql('ALTER TABLE warning_translation DROP FOREIGN KEY FK_D8A1DDE6164006B8');
        $this->addSql('ALTER TABLE menu_translation DROP FOREIGN KEY FK_DC955B23CCD7E912');
        $this->addSql('ALTER TABLE price_translation DROP FOREIGN KEY FK_B3B00979D614C7E7');
        $this->addSql('ALTER TABLE amount DROP FOREIGN KEY FK_8EA170424584665A');
        $this->addSql('ALTER TABLE available DROP FOREIGN KEY FK_A58FA4854584665A');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA74584665A');
        $this->addSql('ALTER TABLE price DROP FOREIGN KEY FK_CAC822D94584665A');
        $this->addSql('ALTER TABLE product_description_translation DROP FOREIGN KEY FK_119EDAE44584665A');
        $this->addSql('ALTER TABLE product_wp_translation DROP FOREIGN KEY FK_268553144584665A');
        $this->addSql('ALTER TABLE warning_translation DROP FOREIGN KEY FK_D8A1DDE6BFF38603');
        $this->addSql('DROP TABLE about_us');
        $this->addSql('DROP TABLE amount');
        $this->addSql('DROP TABLE amount_translation');
        $this->addSql('DROP TABLE available');
        $this->addSql('DROP TABLE banner');
        $this->addSql('DROP TABLE banner_translation');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE category_translation');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE countries');
        $this->addSql('DROP TABLE currency');
        $this->addSql('DROP TABLE easytext');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE gallery');
        $this->addSql('DROP TABLE gallery_translation');
        $this->addSql('DROP TABLE locales');
        $this->addSql('DROP TABLE logs');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE menu_translation');
        $this->addSql('DROP TABLE price');
        $this->addSql('DROP TABLE price_translation');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_description_translation');
        $this->addSql('DROP TABLE product_wp_translation');
        $this->addSql('DROP TABLE rgpd');
        $this->addSql('DROP TABLE terms_conditions');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE warning');
        $this->addSql('DROP TABLE warning_translation');
    }
}
