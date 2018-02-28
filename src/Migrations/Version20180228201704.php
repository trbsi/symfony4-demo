<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180228201704 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE courses_students_pivot DROP FOREIGN KEY FK_DD93D368591CC992');
        $this->addSql('ALTER TABLE courses_students_pivot DROP FOREIGN KEY FK_DD93D368CB944F1A');
        $this->addSql('ALTER TABLE courses_students_pivot ADD CONSTRAINT FK_DD93D368591CC992 FOREIGN KEY (course_id) REFERENCES courses (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE courses_students_pivot ADD CONSTRAINT FK_DD93D368CB944F1A FOREIGN KEY (student_id) REFERENCES students (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE courses_students_pivot DROP FOREIGN KEY FK_DD93D368CB944F1A');
        $this->addSql('ALTER TABLE courses_students_pivot DROP FOREIGN KEY FK_DD93D368591CC992');
        $this->addSql('ALTER TABLE courses_students_pivot ADD CONSTRAINT FK_DD93D368CB944F1A FOREIGN KEY (student_id) REFERENCES students (id)');
        $this->addSql('ALTER TABLE courses_students_pivot ADD CONSTRAINT FK_DD93D368591CC992 FOREIGN KEY (course_id) REFERENCES courses (id)');
    }
}
