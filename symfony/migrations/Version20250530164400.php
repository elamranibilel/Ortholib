<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250530164400 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE orthophoniste ADD CONSTRAINT FK_4C2CDD165627D44C FOREIGN KEY (specialisation_id) REFERENCES specialisation (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4C2CDD165627D44C ON orthophoniste (specialisation_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE patient ADD specialisation_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EB5627D44C FOREIGN KEY (specialisation_id) REFERENCES specialisation (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_1ADAD7EB5627D44C ON patient (specialisation_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE orthophoniste DROP FOREIGN KEY FK_4C2CDD165627D44C
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_4C2CDD165627D44C ON orthophoniste
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EB5627D44C
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_1ADAD7EB5627D44C ON patient
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE patient DROP specialisation_id
        SQL);
    }
}
