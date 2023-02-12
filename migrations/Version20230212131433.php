<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230212131433 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentairenews DROP FOREIGN KEY FK_CBD9E7AC58E0A285');
        $this->addSql('ALTER TABLE commentairenews DROP FOREIGN KEY FK_CBD9E7ACF7BC18C7');
        $this->addSql('ALTER TABLE reviewuserjeux DROP FOREIGN KEY FK_7E8E98F65708A821');
        $this->addSql('ALTER TABLE reviewuserjeux DROP FOREIGN KEY FK_7E8E98F658E0A285');
        $this->addSql('DROP TABLE commentairenews');
        $this->addSql('DROP TABLE jeux');
        $this->addSql('DROP TABLE news');
        $this->addSql('DROP TABLE reviewuserjeux');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commentairenews (id INT AUTO_INCREMENT NOT NULL, newsid_id INT DEFAULT NULL, userid_id INT DEFAULT NULL, descrition VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_CBD9E7ACF7BC18C7 (newsid_id), UNIQUE INDEX UNIQ_CBD9E7AC58E0A285 (userid_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE jeux (id INT AUTO_INCREMENT NOT NULL, nom_game VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, data_ajout DATE NOT NULL, max_joueurs INT NOT NULL, prix_jeux DOUBLE PRECISION NOT NULL, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE news (id INT AUTO_INCREMENT NOT NULL, date_creation DATE NOT NULL, titre VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reviewuserjeux (id INT AUTO_INCREMENT NOT NULL, userid_id INT DEFAULT NULL, jeuxid_id INT DEFAULT NULL, rating INT NOT NULL, UNIQUE INDEX UNIQ_7E8E98F65708A821 (jeuxid_id), UNIQUE INDEX UNIQ_7E8E98F658E0A285 (userid_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE commentairenews ADD CONSTRAINT FK_CBD9E7AC58E0A285 FOREIGN KEY (userid_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentairenews ADD CONSTRAINT FK_CBD9E7ACF7BC18C7 FOREIGN KEY (newsid_id) REFERENCES news (id)');
        $this->addSql('ALTER TABLE reviewuserjeux ADD CONSTRAINT FK_7E8E98F65708A821 FOREIGN KEY (jeuxid_id) REFERENCES jeux (id)');
        $this->addSql('ALTER TABLE reviewuserjeux ADD CONSTRAINT FK_7E8E98F658E0A285 FOREIGN KEY (userid_id) REFERENCES user (id)');
    }
}
