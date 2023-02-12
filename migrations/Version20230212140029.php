<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230212140029 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commentaire_news (id INT AUTO_INCREMENT NOT NULL, id_news_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_F42B5D026B39F0D0 (id_news_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jeux (id INT AUTO_INCREMENT NOT NULL, nom_game VARCHAR(255) NOT NULL, date_add_game DATE NOT NULL, max_players INT NOT NULL, price_game DOUBLE PRECISION NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE membre_groupe (id INT AUTO_INCREMENT NOT NULL, id_groupe_id INT DEFAULT NULL, id_gamer_id INT DEFAULT NULL, date DATETIME NOT NULL, INDEX IDX_9EB01998FA7089AB (id_groupe_id), INDEX IDX_9EB019987F984D83 (id_gamer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE news (id INT AUTO_INCREMENT NOT NULL, id_jeux_id INT DEFAULT NULL, date_n DATE NOT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_1DD3995032B700A2 (id_jeux_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE review_jeux (id INT AUTO_INCREMENT NOT NULL, id_jeux_id INT DEFAULT NULL, id_gamer_id INT DEFAULT NULL, rating INT NOT NULL, INDEX IDX_D5E1F81532B700A2 (id_jeux_id), INDEX IDX_D5E1F8157F984D83 (id_gamer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentaire_news ADD CONSTRAINT FK_F42B5D026B39F0D0 FOREIGN KEY (id_news_id) REFERENCES news (id)');
        $this->addSql('ALTER TABLE membre_groupe ADD CONSTRAINT FK_9EB01998FA7089AB FOREIGN KEY (id_groupe_id) REFERENCES groupe (id)');
        $this->addSql('ALTER TABLE membre_groupe ADD CONSTRAINT FK_9EB019987F984D83 FOREIGN KEY (id_gamer_id) REFERENCES gamer (id)');
        $this->addSql('ALTER TABLE news ADD CONSTRAINT FK_1DD3995032B700A2 FOREIGN KEY (id_jeux_id) REFERENCES jeux (id)');
        $this->addSql('ALTER TABLE review_jeux ADD CONSTRAINT FK_D5E1F81532B700A2 FOREIGN KEY (id_jeux_id) REFERENCES jeux (id)');
        $this->addSql('ALTER TABLE review_jeux ADD CONSTRAINT FK_D5E1F8157F984D83 FOREIGN KEY (id_gamer_id) REFERENCES gamer (id)');
        $this->addSql('ALTER TABLE post ADD id_groupe_id INT DEFAULT NULL, DROP iduser, DROP idgroupe');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DFA7089AB FOREIGN KEY (id_groupe_id) REFERENCES groupe (id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8DFA7089AB ON post (id_groupe_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire_news DROP FOREIGN KEY FK_F42B5D026B39F0D0');
        $this->addSql('ALTER TABLE membre_groupe DROP FOREIGN KEY FK_9EB01998FA7089AB');
        $this->addSql('ALTER TABLE membre_groupe DROP FOREIGN KEY FK_9EB019987F984D83');
        $this->addSql('ALTER TABLE news DROP FOREIGN KEY FK_1DD3995032B700A2');
        $this->addSql('ALTER TABLE review_jeux DROP FOREIGN KEY FK_D5E1F81532B700A2');
        $this->addSql('ALTER TABLE review_jeux DROP FOREIGN KEY FK_D5E1F8157F984D83');
        $this->addSql('DROP TABLE commentaire_news');
        $this->addSql('DROP TABLE jeux');
        $this->addSql('DROP TABLE membre_groupe');
        $this->addSql('DROP TABLE news');
        $this->addSql('DROP TABLE review_jeux');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DFA7089AB');
        $this->addSql('DROP INDEX IDX_5A8A6C8DFA7089AB ON post');
        $this->addSql('ALTER TABLE post ADD iduser INT NOT NULL, ADD idgroupe INT NOT NULL, DROP id_groupe_id');
    }
}
