<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251128071101 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE dokumentazioa_bak');
        $this->addSql('ALTER TABLE eremuak DROP FOREIGN KEY FK_6E2A8F987B9BE29C');
        $this->addSql('ALTER TABLE eremuak ADD CONSTRAINT FK_6E2A8F987B9BE29C FOREIGN KEY (udala_id) REFERENCES udala (id)');
        $this->addSql('ALTER TABLE fitxa CHANGE jarraibideakeu jarraibideakeu LONGTEXT DEFAULT NULL, CHANGE jarraibideakes jarraibideakes LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE udala DROP FOREIGN KEY FK_F9BC2C84F2E16144');
        $this->addSql('DROP INDEX UNIQ_F9BC2C84F2E16144 ON udala');
        $this->addSql('ALTER TABLE udala DROP eremuak_id');
        $this->addSql('ALTER TABLE user CHANGE username username VARCHAR(180) NOT NULL, CHANGE password password VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dokumentazioa_bak (id BIGINT DEFAULT 0 NOT NULL, kodea VARCHAR(255) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, dokumentumota_id BIGINT DEFAULT NULL, deskribapenaeu TEXT CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, deskribapenaes TEXT CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, estekaeu VARCHAR(255) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, estekaes VARCHAR(255) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, udala_id INT DEFAULT NULL) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE eremuak DROP FOREIGN KEY FK_6E2A8F987B9BE29C');
        $this->addSql('ALTER TABLE eremuak ADD CONSTRAINT FK_6E2A8F987B9BE29C FOREIGN KEY (udala_id) REFERENCES udala (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fitxa CHANGE jarraibideakeu jarraibideakeu TEXT DEFAULT NULL, CHANGE jarraibideakes jarraibideakes TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE udala ADD eremuak_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE udala ADD CONSTRAINT FK_F9BC2C84F2E16144 FOREIGN KEY (eremuak_id) REFERENCES eremuak (id) ON DELETE SET NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F9BC2C84F2E16144 ON udala (eremuak_id)');
        $this->addSql('ALTER TABLE user CHANGE username username VARCHAR(180) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, CHANGE password password VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`');
    }
}
