<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250214110150 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE exercice (id INT AUTO_INCREMENT NOT NULL, patient_id INT NOT NULL, orthophoniste_id INT NOT NULL, type VARCHAR(255) NOT NULL, chronometre INT DEFAULT NULL, score INT DEFAULT NULL, INDEX IDX_E418C74D6B899279 (patient_id), INDEX IDX_E418C74D78A4CD0F (orthophoniste_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exercice_signification (exercice_id INT NOT NULL, signification_id INT NOT NULL, INDEX IDX_9663C1C189D40298 (exercice_id), INDEX IDX_9663C1C14DC030D (signification_id), PRIMARY KEY(exercice_id, signification_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE signification (id INT AUTO_INCREMENT NOT NULL, mots VARCHAR(255) DEFAULT NULL, definition LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE exercice ADD CONSTRAINT FK_E418C74D6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE exercice ADD CONSTRAINT FK_E418C74D78A4CD0F FOREIGN KEY (orthophoniste_id) REFERENCES orthophoniste (id)');
        $this->addSql('ALTER TABLE exercice_signification ADD CONSTRAINT FK_9663C1C189D40298 FOREIGN KEY (exercice_id) REFERENCES exercice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE exercice_signification ADD CONSTRAINT FK_9663C1C14DC030D FOREIGN KEY (signification_id) REFERENCES signification (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC71E27F6BF');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC76B899279');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E8C9E392E');
        $this->addSql('ALTER TABLE jeu DROP FOREIGN KEY FK_82E48DB56B899279');
        $this->addSql('ALTER TABLE score DROP FOREIGN KEY FK_329937516B899279');
        $this->addSql('ALTER TABLE score DROP FOREIGN KEY FK_329937518C9E392E');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE jeu');
        $this->addSql('DROP TABLE score');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reponse (id INT AUTO_INCREMENT NOT NULL, question_id INT NOT NULL, patient_id INT NOT NULL, reponse_donnee LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, correcte TINYINT(1) NOT NULL, INDEX IDX_5FB6DEC71E27F6BF (question_id), INDEX IDX_5FB6DEC76B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, jeu_id INT NOT NULL, type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, contenu LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, reponse_correcte LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, options LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_B6F7494E8C9E392E (jeu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE jeu (id INT AUTO_INCREMENT NOT NULL, patient_id INT NOT NULL, type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, niveau VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, chronometre INT DEFAULT NULL, seuil_reussite DOUBLE PRECISION NOT NULL, INDEX IDX_82E48DB56B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE score (id INT AUTO_INCREMENT NOT NULL, patient_id INT NOT NULL, jeu_id INT NOT NULL, score DOUBLE PRECISION NOT NULL, temps_restant INT NOT NULL, INDEX IDX_329937516B899279 (patient_id), INDEX IDX_329937518C9E392E (jeu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC71E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC76B899279 FOREIGN KEY (patient_id) REFERENCES patient (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E8C9E392E FOREIGN KEY (jeu_id) REFERENCES jeu (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE jeu ADD CONSTRAINT FK_82E48DB56B899279 FOREIGN KEY (patient_id) REFERENCES patient (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE score ADD CONSTRAINT FK_329937516B899279 FOREIGN KEY (patient_id) REFERENCES patient (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE score ADD CONSTRAINT FK_329937518C9E392E FOREIGN KEY (jeu_id) REFERENCES jeu (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE exercice DROP FOREIGN KEY FK_E418C74D6B899279');
        $this->addSql('ALTER TABLE exercice DROP FOREIGN KEY FK_E418C74D78A4CD0F');
        $this->addSql('ALTER TABLE exercice_signification DROP FOREIGN KEY FK_9663C1C189D40298');
        $this->addSql('ALTER TABLE exercice_signification DROP FOREIGN KEY FK_9663C1C14DC030D');
        $this->addSql('DROP TABLE exercice');
        $this->addSql('DROP TABLE exercice_signification');
        $this->addSql('DROP TABLE signification');
    }
}
