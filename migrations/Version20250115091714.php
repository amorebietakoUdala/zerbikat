<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250115091714 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fitxa DROP FOREIGN KEY FK_18D5AFC54CEC5FFE');
        $this->addSql('DROP TABLE fitxa_familia');
        $this->addSql('ALTER TABLE fitxa ADD CONSTRAINT FK_18D5AFC54CEC5FFE FOREIGN KEY (nork_sortua_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6497B9BE29C');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649B49BC7A0 FOREIGN KEY (azpisaila_id) REFERENCES azpisaila (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6497B9BE29C FOREIGN KEY (udala_id) REFERENCES udala (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_8D93D649B49BC7A0 ON user (azpisaila_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE dokumentazioa_bak (id BIGINT DEFAULT 0 NOT NULL, kodea VARCHAR(255) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, dokumentumota_id BIGINT DEFAULT NULL, deskribapenaeu TEXT CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, deskribapenaes TEXT CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, estekaeu VARCHAR(255) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, estekaes VARCHAR(255) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, udala_id INT DEFAULT NULL) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE fitxa_familia (fitxa_id BIGINT NOT NULL, familia_id BIGINT NOT NULL, INDEX IDX_BBE798392E0317FE (fitxa_id), INDEX IDX_BBE79839D02563A3 (familia_id), PRIMARY KEY(fitxa_id, familia_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE fos_user (id INT AUTO_INCREMENT NOT NULL, udala_id INT DEFAULT NULL, azpisaila_id BIGINT DEFAULT NULL, username VARCHAR(180) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, username_canonical VARCHAR(180) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, email VARCHAR(180) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, email_canonical VARCHAR(180) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_unicode_ci`, password VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_unicode_ci`, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:array)\', INDEX IDX_957A64797B9BE29C (udala_id), INDEX IDX_957A6479B49BC7A0 (azpisaila_id), UNIQUE INDEX UNIQ_957A647992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_957A6479A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_957A6479C05FB297 (confirmation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE fitxa_familia ADD CONSTRAINT FK_BBE798392E0317FE FOREIGN KEY (fitxa_id) REFERENCES fitxa (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fitxa_familia ADD CONSTRAINT FK_BBE79839D02563A3 FOREIGN KEY (familia_id) REFERENCES familia (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fos_user ADD CONSTRAINT FK_957A64797B9BE29C FOREIGN KEY (udala_id) REFERENCES udala (id) ON UPDATE NO ACTION ON DELETE SET NULL');
        $this->addSql('ALTER TABLE fos_user ADD CONSTRAINT FK_957A6479B49BC7A0 FOREIGN KEY (azpisaila_id) REFERENCES azpisaila (id) ON UPDATE NO ACTION ON DELETE SET NULL');
        $this->addSql('ALTER TABLE fitxa DROP FOREIGN KEY FK_18D5AFC54CEC5FFE');
        $this->addSql('ALTER TABLE fitxa ADD CONSTRAINT FK_18D5AFC54CEC5FFE FOREIGN KEY (nork_sortua_id) REFERENCES fos_user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649B49BC7A0');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6497B9BE29C');
        $this->addSql('DROP INDEX IDX_8D93D649B49BC7A0 ON user');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6497B9BE29C FOREIGN KEY (udala_id) REFERENCES udala (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
