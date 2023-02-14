<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230214171759 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE groupe CHANGE nom_groupe nom_groupe VARCHAR(255) DEFAULT NULL, CHANGE image image VARCHAR(255) DEFAULT NULL, CHANGE description description VARCHAR(255) DEFAULT NULL, CHANGE nbr_user nbr_user INT DEFAULT NULL, CHANGE nbr_max nbr_max INT DEFAULT NULL, CHANGE id_owner id_owner INT DEFAULT NULL');
        $this->addSql('ALTER TABLE news CHANGE description description VARCHAR(65535) NOT NULL');
        $this->addSql('ALTER TABLE produit ADD image VARCHAR(255) DEFAULT NULL, CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE prix prix INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE groupe CHANGE nom_groupe nom_groupe VARCHAR(255) NOT NULL, CHANGE image image VARCHAR(255) NOT NULL, CHANGE description description VARCHAR(255) NOT NULL, CHANGE nbr_user nbr_user INT NOT NULL, CHANGE nbr_max nbr_max INT NOT NULL, CHANGE id_owner id_owner INT NOT NULL');
        $this->addSql('ALTER TABLE news CHANGE description description MEDIUMTEXT NOT NULL');
        $this->addSql('ALTER TABLE produit DROP image, CHANGE nom nom INT NOT NULL, CHANGE prix prix VARCHAR(255) NOT NULL');
    }
}
