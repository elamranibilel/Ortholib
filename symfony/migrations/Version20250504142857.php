<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250504142857 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE administrateur (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE cabinet (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, auteur_id INT NOT NULL, destinataire_id INT NOT NULL, texte LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', role_auteur VARCHAR(255) DEFAULT NULL, role_destinataire VARCHAR(255) DEFAULT NULL, INDEX IDX_67F068BC60BB6FE6 (auteur_id), INDEX IDX_67F068BCA4F84F6E (destinataire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE exercice (id INT AUTO_INCREMENT NOT NULL, patient_id INT NOT NULL, orthophoniste_id INT NOT NULL, type VARCHAR(255) NOT NULL, chronometre INT DEFAULT NULL, INDEX IDX_E418C74D6B899279 (patient_id), INDEX IDX_E418C74D78A4CD0F (orthophoniste_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE exercice_signification (exercice_id INT NOT NULL, signification_id INT NOT NULL, INDEX IDX_9663C1C189D40298 (exercice_id), INDEX IDX_9663C1C14DC030D (signification_id), PRIMARY KEY(exercice_id, signification_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, cabinet_id INT DEFAULT NULL, orthophoniste_id INT DEFAULT NULL, patient_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, INDEX IDX_C53D045FD351EC (cabinet_id), INDEX IDX_C53D045F78A4CD0F (orthophoniste_id), INDEX IDX_C53D045F6B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE orthophoniste (id INT NOT NULL, cabinet_id INT NOT NULL, nombre_heure_travail INT DEFAULT NULL, specialisation VARCHAR(255) NOT NULL, INDEX IDX_4C2CDD16D351EC (cabinet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE patient (id INT NOT NULL, choix_ortho_id INT DEFAULT NULL, niveau_langue VARCHAR(255) NOT NULL, difficulte_test VARCHAR(255) NOT NULL, niveau_apprentissage VARCHAR(255) NOT NULL, deficient VARCHAR(255) NOT NULL, difficulte_rencontrees JSON NOT NULL, is_confirmed TINYINT(1) NOT NULL, INDEX IDX_1ADAD7EBFD553130 (choix_ortho_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE resultat_exercice (id INT AUTO_INCREMENT NOT NULL, exercice_id INT NOT NULL, patient_id INT NOT NULL, score INT NOT NULL, date DATETIME NOT NULL, INDEX IDX_B2BEB1D289D40298 (exercice_id), INDEX IDX_B2BEB1D26B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE seances (id INT AUTO_INCREMENT NOT NULL, orthophoniste_id INT NOT NULL, patient_id INT NOT NULL, date_heure_debut DATETIME NOT NULL, date_heure_fin DATETIME NOT NULL, mode VARCHAR(255) DEFAULT NULL, duree VARCHAR(8) NOT NULL, exercices LONGTEXT NOT NULL, INDEX IDX_FC699FF178A4CD0F (orthophoniste_id), INDEX IDX_FC699FF16B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE signification (id INT AUTO_INCREMENT NOT NULL, mots VARCHAR(255) DEFAULT NULL, definition LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', genre VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE administrateur ADD CONSTRAINT FK_32EB52E8BF396750 FOREIGN KEY (id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC60BB6FE6 FOREIGN KEY (auteur_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCA4F84F6E FOREIGN KEY (destinataire_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE exercice ADD CONSTRAINT FK_E418C74D6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE exercice ADD CONSTRAINT FK_E418C74D78A4CD0F FOREIGN KEY (orthophoniste_id) REFERENCES orthophoniste (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE exercice_signification ADD CONSTRAINT FK_9663C1C189D40298 FOREIGN KEY (exercice_id) REFERENCES exercice (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE exercice_signification ADD CONSTRAINT FK_9663C1C14DC030D FOREIGN KEY (signification_id) REFERENCES signification (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image ADD CONSTRAINT FK_C53D045FD351EC FOREIGN KEY (cabinet_id) REFERENCES cabinet (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image ADD CONSTRAINT FK_C53D045F78A4CD0F FOREIGN KEY (orthophoniste_id) REFERENCES orthophoniste (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image ADD CONSTRAINT FK_C53D045F6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE orthophoniste ADD CONSTRAINT FK_4C2CDD16D351EC FOREIGN KEY (cabinet_id) REFERENCES cabinet (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE orthophoniste ADD CONSTRAINT FK_4C2CDD16BF396750 FOREIGN KEY (id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EBFD553130 FOREIGN KEY (choix_ortho_id) REFERENCES orthophoniste (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EBBF396750 FOREIGN KEY (id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resultat_exercice ADD CONSTRAINT FK_B2BEB1D289D40298 FOREIGN KEY (exercice_id) REFERENCES exercice (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resultat_exercice ADD CONSTRAINT FK_B2BEB1D26B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE seances ADD CONSTRAINT FK_FC699FF178A4CD0F FOREIGN KEY (orthophoniste_id) REFERENCES orthophoniste (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE seances ADD CONSTRAINT FK_FC699FF16B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE administrateur DROP FOREIGN KEY FK_32EB52E8BF396750
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC60BB6FE6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCA4F84F6E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE exercice DROP FOREIGN KEY FK_E418C74D6B899279
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE exercice DROP FOREIGN KEY FK_E418C74D78A4CD0F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE exercice_signification DROP FOREIGN KEY FK_9663C1C189D40298
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE exercice_signification DROP FOREIGN KEY FK_9663C1C14DC030D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image DROP FOREIGN KEY FK_C53D045FD351EC
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image DROP FOREIGN KEY FK_C53D045F78A4CD0F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image DROP FOREIGN KEY FK_C53D045F6B899279
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE orthophoniste DROP FOREIGN KEY FK_4C2CDD16D351EC
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE orthophoniste DROP FOREIGN KEY FK_4C2CDD16BF396750
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EBFD553130
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EBBF396750
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resultat_exercice DROP FOREIGN KEY FK_B2BEB1D289D40298
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resultat_exercice DROP FOREIGN KEY FK_B2BEB1D26B899279
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE seances DROP FOREIGN KEY FK_FC699FF178A4CD0F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE seances DROP FOREIGN KEY FK_FC699FF16B899279
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE administrateur
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE cabinet
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE commentaire
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE exercice
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE exercice_signification
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE image
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE orthophoniste
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE patient
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE resultat_exercice
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE seances
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE signification
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE users
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
