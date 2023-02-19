<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230215141341 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE historique_point ADD userid_id INT NOT NULL');
        $this->addSql('ALTER TABLE historique_point ADD CONSTRAINT FK_F925295758E0A285 FOREIGN KEY (userid_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_F925295758E0A285 ON historique_point (userid_id)');
        $this->addSql('ALTER TABLE news CHANGE description description VARCHAR(65535) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE historique_point DROP FOREIGN KEY FK_F925295758E0A285');
        $this->addSql('DROP INDEX IDX_F925295758E0A285 ON historique_point');
        $this->addSql('ALTER TABLE historique_point DROP userid_id');
        $this->addSql('ALTER TABLE news CHANGE description description MEDIUMTEXT NOT NULL');
    }
}
