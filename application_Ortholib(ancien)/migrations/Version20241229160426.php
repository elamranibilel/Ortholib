<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241229160426 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE level (id INT AUTO_INCREMENT NOT NULL, game_level_id INT NOT NULL, difficulty VARCHAR(255) NOT NULL, time_limit INT NOT NULL, success_threshold INT NOT NULL, INDEX IDX_9AEACC13F4F5B481 (game_level_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE score (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, game_id INT NOT NULL, level_id INT NOT NULL, score INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_32993751A76ED395 (user_id), INDEX IDX_32993751E48FD905 (game_id), INDEX IDX_329937515FB14BA7 (level_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE level ADD CONSTRAINT FK_9AEACC13F4F5B481 FOREIGN KEY (game_level_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE score ADD CONSTRAINT FK_32993751A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE score ADD CONSTRAINT FK_32993751E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE score ADD CONSTRAINT FK_329937515FB14BA7 FOREIGN KEY (level_id) REFERENCES level (id)');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EBF3CAFA7');
        $this->addSql('DROP INDEX IDX_B6F7494EBF3CAFA7 ON question');
        $this->addSql('ALTER TABLE question ADD content VARCHAR(255) NOT NULL, ADD type VARCHAR(255) NOT NULL, ADD options LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', DROP user_level_id, DROP texte, CHANGE level game_question_id INT NOT NULL');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EC072B4BD FOREIGN KEY (game_question_id) REFERENCES game (id)');
        $this->addSql('CREATE INDEX IDX_B6F7494EC072B4BD ON question (game_question_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EC072B4BD');
        $this->addSql('ALTER TABLE level DROP FOREIGN KEY FK_9AEACC13F4F5B481');
        $this->addSql('ALTER TABLE score DROP FOREIGN KEY FK_32993751A76ED395');
        $this->addSql('ALTER TABLE score DROP FOREIGN KEY FK_32993751E48FD905');
        $this->addSql('ALTER TABLE score DROP FOREIGN KEY FK_329937515FB14BA7');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE level');
        $this->addSql('DROP TABLE score');
        $this->addSql('DROP INDEX IDX_B6F7494EC072B4BD ON question');
        $this->addSql('ALTER TABLE question ADD user_level_id INT DEFAULT NULL, ADD texte LONGTEXT NOT NULL, DROP content, DROP type, DROP options, CHANGE game_question_id level INT NOT NULL');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EBF3CAFA7 FOREIGN KEY (user_level_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_B6F7494EBF3CAFA7 ON question (user_level_id)');
    }
}
