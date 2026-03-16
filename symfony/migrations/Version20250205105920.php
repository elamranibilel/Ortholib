<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250205105920 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cabinet (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE orthophoniste ADD cabinet_id INT NOT NULL');
        $this->addSql('ALTER TABLE orthophoniste ADD CONSTRAINT FK_4C2CDD16D351EC FOREIGN KEY (cabinet_id) REFERENCES cabinet (id)');
        $this->addSql('CREATE INDEX IDX_4C2CDD16D351EC ON orthophoniste (cabinet_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orthophoniste DROP FOREIGN KEY FK_4C2CDD16D351EC');
        $this->addSql('DROP TABLE cabinet');
        $this->addSql('DROP INDEX IDX_4C2CDD16D351EC ON orthophoniste');
        $this->addSql('ALTER TABLE orthophoniste DROP cabinet_id');
    }
}
