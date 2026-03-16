<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250209121705 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reponse (id INT AUTO_INCREMENT NOT NULL, question_id INT NOT NULL, patient_id INT NOT NULL, reponse_donnee LONGTEXT NOT NULL, correcte TINYINT(1) NOT NULL, INDEX IDX_5FB6DEC71E27F6BF (question_id), INDEX IDX_5FB6DEC76B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE score (id INT AUTO_INCREMENT NOT NULL, patient_id INT NOT NULL, jeu_id INT NOT NULL, score DOUBLE PRECISION NOT NULL, temps_restant INT NOT NULL, INDEX IDX_329937516B899279 (patient_id), INDEX IDX_329937518C9E392E (jeu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC71E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC76B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE score ADD CONSTRAINT FK_329937516B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE score ADD CONSTRAINT FK_329937518C9E392E FOREIGN KEY (jeu_id) REFERENCES jeu (id)');
        $this->addSql('ALTER TABLE question ADD jeu_id INT NOT NULL, ADD type VARCHAR(255) NOT NULL, ADD contenu LONGTEXT NOT NULL, ADD reponse_correcte LONGTEXT NOT NULL, ADD options LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E8C9E392E FOREIGN KEY (jeu_id) REFERENCES jeu (id)');
        $this->addSql('CREATE INDEX IDX_B6F7494E8C9E392E ON question (jeu_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC71E27F6BF');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC76B899279');
        $this->addSql('ALTER TABLE score DROP FOREIGN KEY FK_329937516B899279');
        $this->addSql('ALTER TABLE score DROP FOREIGN KEY FK_329937518C9E392E');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('DROP TABLE score');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E8C9E392E');
        $this->addSql('DROP INDEX IDX_B6F7494E8C9E392E ON question');
        $this->addSql('ALTER TABLE question DROP jeu_id, DROP type, DROP contenu, DROP reponse_correcte, DROP options');
    }
}
