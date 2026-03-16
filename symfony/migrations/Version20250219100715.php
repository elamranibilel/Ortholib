<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250219100715 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE administrateur (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cabinet (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exercice (id INT AUTO_INCREMENT NOT NULL, patient_id INT NOT NULL, orthophoniste_id INT NOT NULL, type VARCHAR(255) NOT NULL, chronometre INT DEFAULT NULL, INDEX IDX_E418C74D6B899279 (patient_id), INDEX IDX_E418C74D78A4CD0F (orthophoniste_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exercice_signification (exercice_id INT NOT NULL, signification_id INT NOT NULL, INDEX IDX_9663C1C189D40298 (exercice_id), INDEX IDX_9663C1C14DC030D (signification_id), PRIMARY KEY(exercice_id, signification_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, cabinet_id INT DEFAULT NULL, orthophoniste_id INT DEFAULT NULL, patient_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, INDEX IDX_C53D045FD351EC (cabinet_id), INDEX IDX_C53D045F78A4CD0F (orthophoniste_id), INDEX IDX_C53D045F6B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE orthophoniste (id INT NOT NULL, cabinet_id INT NOT NULL, nombre_heure_travail INT DEFAULT NULL, specialisation VARCHAR(255) NOT NULL, INDEX IDX_4C2CDD16D351EC (cabinet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE patient (id INT NOT NULL, choix_ortho_id INT DEFAULT NULL, niveau_langue VARCHAR(255) NOT NULL, difficulte_test VARCHAR(255) NOT NULL, niveau_apprentissage VARCHAR(255) NOT NULL, deficient VARCHAR(255) NOT NULL, difficulte_rencontrees JSON NOT NULL, INDEX IDX_1ADAD7EBFD553130 (choix_ortho_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resultat_exercice (id INT AUTO_INCREMENT NOT NULL, exercice_id INT NOT NULL, patient_id INT NOT NULL, score INT NOT NULL, date DATETIME NOT NULL, INDEX IDX_B2BEB1D289D40298 (exercice_id), INDEX IDX_B2BEB1D26B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE seances (id INT AUTO_INCREMENT NOT NULL, orthophoniste_id INT NOT NULL, patient_id INT NOT NULL, date_heure_debut DATETIME NOT NULL, date_heure_fin DATETIME NOT NULL, mode VARCHAR(255) DEFAULT NULL, duree VARCHAR(8) NOT NULL, exercices LONGTEXT NOT NULL, INDEX IDX_FC699FF178A4CD0F (orthophoniste_id), INDEX IDX_FC699FF16B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE signification (id INT AUTO_INCREMENT NOT NULL, mots VARCHAR(255) DEFAULT NULL, definition LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', genre VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE administrateur ADD CONSTRAINT FK_32EB52E8BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE exercice ADD CONSTRAINT FK_E418C74D6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE exercice ADD CONSTRAINT FK_E418C74D78A4CD0F FOREIGN KEY (orthophoniste_id) REFERENCES orthophoniste (id)');
        $this->addSql('ALTER TABLE exercice_signification ADD CONSTRAINT FK_9663C1C189D40298 FOREIGN KEY (exercice_id) REFERENCES exercice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE exercice_signification ADD CONSTRAINT FK_9663C1C14DC030D FOREIGN KEY (signification_id) REFERENCES signification (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FD351EC FOREIGN KEY (cabinet_id) REFERENCES cabinet (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F78A4CD0F FOREIGN KEY (orthophoniste_id) REFERENCES orthophoniste (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE orthophoniste ADD CONSTRAINT FK_4C2CDD16D351EC FOREIGN KEY (cabinet_id) REFERENCES cabinet (id)');
        $this->addSql('ALTER TABLE orthophoniste ADD CONSTRAINT FK_4C2CDD16BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EBFD553130 FOREIGN KEY (choix_ortho_id) REFERENCES orthophoniste (id)');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EBBF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE resultat_exercice ADD CONSTRAINT FK_B2BEB1D289D40298 FOREIGN KEY (exercice_id) REFERENCES exercice (id)');
        $this->addSql('ALTER TABLE resultat_exercice ADD CONSTRAINT FK_B2BEB1D26B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE seances ADD CONSTRAINT FK_FC699FF178A4CD0F FOREIGN KEY (orthophoniste_id) REFERENCES orthophoniste (id)');
        $this->addSql('ALTER TABLE seances ADD CONSTRAINT FK_FC699FF16B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE administrateur DROP FOREIGN KEY FK_32EB52E8BF396750');
        $this->addSql('ALTER TABLE exercice DROP FOREIGN KEY FK_E418C74D6B899279');
        $this->addSql('ALTER TABLE exercice DROP FOREIGN KEY FK_E418C74D78A4CD0F');
        $this->addSql('ALTER TABLE exercice_signification DROP FOREIGN KEY FK_9663C1C189D40298');
        $this->addSql('ALTER TABLE exercice_signification DROP FOREIGN KEY FK_9663C1C14DC030D');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FD351EC');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F78A4CD0F');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F6B899279');
        $this->addSql('ALTER TABLE orthophoniste DROP FOREIGN KEY FK_4C2CDD16D351EC');
        $this->addSql('ALTER TABLE orthophoniste DROP FOREIGN KEY FK_4C2CDD16BF396750');
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EBFD553130');
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EBBF396750');
        $this->addSql('ALTER TABLE resultat_exercice DROP FOREIGN KEY FK_B2BEB1D289D40298');
        $this->addSql('ALTER TABLE resultat_exercice DROP FOREIGN KEY FK_B2BEB1D26B899279');
        $this->addSql('ALTER TABLE seances DROP FOREIGN KEY FK_FC699FF178A4CD0F');
        $this->addSql('ALTER TABLE seances DROP FOREIGN KEY FK_FC699FF16B899279');
        $this->addSql('DROP TABLE administrateur');
        $this->addSql('DROP TABLE cabinet');
        $this->addSql('DROP TABLE exercice');
        $this->addSql('DROP TABLE exercice_signification');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE orthophoniste');
        $this->addSql('DROP TABLE patient');
        $this->addSql('DROP TABLE resultat_exercice');
        $this->addSql('DROP TABLE seances');
        $this->addSql('DROP TABLE signification');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
