<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250213083418 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE seances (id INT AUTO_INCREMENT NOT NULL, orthophoniste_id INT NOT NULL, patient_id INT NOT NULL, date_heure_debut DATETIME NOT NULL, date_heure_fin DATETIME NOT NULL, mode VARCHAR(255) DEFAULT NULL, duree INT NOT NULL, INDEX IDX_FC699FF178A4CD0F (orthophoniste_id), INDEX IDX_FC699FF16B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE seances ADD CONSTRAINT FK_FC699FF178A4CD0F FOREIGN KEY (orthophoniste_id) REFERENCES orthophoniste (id)');
        $this->addSql('ALTER TABLE seances ADD CONSTRAINT FK_FC699FF16B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE cabinet DROP latitude, DROP longitude');
        $this->addSql('ALTER TABLE orthophoniste DROP latitude, DROP longitude, DROP adresse');
        $this->addSql('ALTER TABLE patient DROP latitude, DROP longitude, DROP adresse');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE seances DROP FOREIGN KEY FK_FC699FF178A4CD0F');
        $this->addSql('ALTER TABLE seances DROP FOREIGN KEY FK_FC699FF16B899279');
        $this->addSql('DROP TABLE seances');
        $this->addSql('ALTER TABLE cabinet ADD latitude VARCHAR(255) NOT NULL, ADD longitude VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE orthophoniste ADD latitude VARCHAR(255) DEFAULT NULL, ADD longitude VARCHAR(255) DEFAULT NULL, ADD adresse VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE patient ADD latitude VARCHAR(255) DEFAULT NULL, ADD longitude VARCHAR(255) DEFAULT NULL, ADD adresse VARCHAR(255) NOT NULL');
    }
}
