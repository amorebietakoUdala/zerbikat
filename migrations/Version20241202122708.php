<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241202122708 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('CREATE TABLE user as SELECT * FROM fos_user');
        $this->addSql('ALTER TABLE user ADD first_name VARCHAR(255) NOT NULL, ADD activated TINYINT(1) DEFAULT \'1\' NOT NULL, DROP username_canonical, DROP email_canonical, DROP salt, DROP confirmation_token, DROP password_requested_at, CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE email email VARCHAR(255) NOT NULL, CHANGE roles roles JSON NOT NULL, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6497B9BE29C FOREIGN KEY (udala_id) REFERENCES udala (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
        $this->addSql('CREATE INDEX IDX_8D93D6497B9BE29C ON user (udala_id)');
        $this->addSql('UPDATE user SET first_name=username, activated=enabled');
        $this->addSql('ALTER TABLE user DROP enabled');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6497B9BE29C');
        $this->addSql('DROP INDEX IDX_8D93D6497B9BE29C ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D649F85E0677 ON user');
        $this->addSql('DROP TABLE user');
    }
}
