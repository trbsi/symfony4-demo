<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180226194754 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE universities (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, uni_key VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE students ADD university_id INT NOT NULL AFTER name');
        $this->addSql('ALTER TABLE students ADD CONSTRAINT FK_A4698DB2309D1878 FOREIGN KEY (university_id) REFERENCES universities (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A4698DB2309D1878 ON students (university_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE students DROP FOREIGN KEY FK_A4698DB2309D1878');
        $this->addSql('DROP TABLE universities');
        $this->addSql('DROP INDEX UNIQ_A4698DB2309D1878 ON students');
        $this->addSql('ALTER TABLE students DROP university_id');
    }
}
