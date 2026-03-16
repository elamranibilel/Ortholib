<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250215111526 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE resultat_exercice (id INT AUTO_INCREMENT NOT NULL, exercice_id INT NOT NULL, patient_id INT NOT NULL, score INT NOT NULL, INDEX IDX_B2BEB1D289D40298 (exercice_id), INDEX IDX_B2BEB1D26B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE resultat_exercice ADD CONSTRAINT FK_B2BEB1D289D40298 FOREIGN KEY (exercice_id) REFERENCES exercice (id)');
        $this->addSql('ALTER TABLE resultat_exercice ADD CONSTRAINT FK_B2BEB1D26B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE exercice DROP score');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resultat_exercice DROP FOREIGN KEY FK_B2BEB1D289D40298');
        $this->addSql('ALTER TABLE resultat_exercice DROP FOREIGN KEY FK_B2BEB1D26B899279');
        $this->addSql('DROP TABLE resultat_exercice');
        $this->addSql('ALTER TABLE exercice ADD score INT DEFAULT NULL');
    }
}
