<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230213195414 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE classement (id INT AUTO_INCREMENT NOT NULL, id_tournois_id INT DEFAULT NULL, id_team_id INT DEFAULT NULL, score DOUBLE PRECISION NOT NULL, INDEX IDX_55EE9D6D1F409EBB (id_tournois_id), INDEX IDX_55EE9D6DF7F171DE (id_team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE coach (id INT NOT NULL, etat TINYINT(1) NOT NULL, review DOUBLE PRECISION DEFAULT NULL, prix_heure DOUBLE PRECISION NOT NULL, cv VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire_news (id INT AUTO_INCREMENT NOT NULL, id_news_id INT DEFAULT NULL, user_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL, date DATETIME NOT NULL, INDEX IDX_F42B5D026B39F0D0 (id_news_id), INDEX IDX_F42B5D02A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cours (id INT AUTO_INCREMENT NOT NULL, id_coach_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, video VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, prix INT NOT NULL, niveau VARCHAR(255) DEFAULT NULL, INDEX IDX_FDCA8C9C6CCBBA04 (id_coach_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gamer (id INT NOT NULL, tag VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe (id INT AUTO_INCREMENT NOT NULL, nom_groupe VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, nbr_user INT NOT NULL, nbr_max INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE historique_achat (id INT AUTO_INCREMENT NOT NULL, id_gamer_id INT DEFAULT NULL, idproduit_id INT DEFAULT NULL, date_dachat DATE NOT NULL, reference VARCHAR(255) NOT NULL, INDEX IDX_68295E257F984D83 (id_gamer_id), INDEX IDX_68295E25C29D63C1 (idproduit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE historique_point (id INT AUTO_INCREMENT NOT NULL, dates DATE NOT NULL, point DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jeux (id INT AUTO_INCREMENT NOT NULL, nom_game VARCHAR(255) NOT NULL, date_add_game DATE NOT NULL, max_players INT NOT NULL, price_game DOUBLE PRECISION NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE membre (id INT AUTO_INCREMENT NOT NULL, id_gamer_id INT DEFAULT NULL, id_team_id INT DEFAULT NULL, point INT NOT NULL, INDEX IDX_F6B4FB297F984D83 (id_gamer_id), INDEX IDX_F6B4FB29F7F171DE (id_team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE membre_groupe (id INT AUTO_INCREMENT NOT NULL, id_groupe_id INT DEFAULT NULL, id_gamer_id INT DEFAULT NULL, date DATETIME NOT NULL, INDEX IDX_9EB01998FA7089AB (id_groupe_id), INDEX IDX_9EB019987F984D83 (id_gamer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE news (id INT AUTO_INCREMENT NOT NULL, id_jeux_id INT DEFAULT NULL, date_n DATETIME NOT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(65535) NOT NULL, INDEX IDX_1DD3995032B700A2 (id_jeux_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE planning (id INT AUTO_INCREMENT NOT NULL, id_gamer_id INT DEFAULT NULL, id_coach_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, date DATETIME NOT NULL, description VARCHAR(255) NOT NULL, url_meet VARCHAR(255) DEFAULT NULL, etat TINYINT(1) NOT NULL, nbre_heure_seance INT NOT NULL, prix_heure INT DEFAULT NULL, INDEX IDX_D499BFF67F984D83 (id_gamer_id), INDEX IDX_D499BFF66CCBBA04 (id_coach_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, id_groupe_id INT DEFAULT NULL, nompost VARCHAR(255) NOT NULL, contenu VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, nbr_like INT NOT NULL, INDEX IDX_5A8A6C8DFA7089AB (id_groupe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, id_categorie_id INT DEFAULT NULL, nom INT NOT NULL, prix VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, INDEX IDX_29A5EC279F34925F (id_categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE review_jeux (id INT AUTO_INCREMENT NOT NULL, id_jeux_id INT DEFAULT NULL, id_gamer_id INT DEFAULT NULL, rating INT NOT NULL, INDEX IDX_D5E1F81532B700A2 (id_jeux_id), INDEX IDX_D5E1F8157F984D83 (id_gamer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, idowner INT NOT NULL, nom_team VARCHAR(255) NOT NULL, nb_joueurs INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournoi (id INT AUTO_INCREMENT NOT NULL, nb_team INT NOT NULL, nb_joueur_team INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, photo_url VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, date_naissance DATE NOT NULL, point DOUBLE PRECISION NOT NULL, discriminator VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_courses (id INT AUTO_INCREMENT NOT NULL, id_gamer_id INT DEFAULT NULL, id_cours_id INT DEFAULT NULL, favori TINYINT(1) NOT NULL, acheter TINYINT(1) NOT NULL, description VARCHAR(255) DEFAULT NULL, review INT DEFAULT NULL, INDEX IDX_F1A844467F984D83 (id_gamer_id), INDEX IDX_F1A844462E149425 (id_cours_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE classement ADD CONSTRAINT FK_55EE9D6D1F409EBB FOREIGN KEY (id_tournois_id) REFERENCES tournoi (id)');
        $this->addSql('ALTER TABLE classement ADD CONSTRAINT FK_55EE9D6DF7F171DE FOREIGN KEY (id_team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE coach ADD CONSTRAINT FK_3F596DCCBF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaire_news ADD CONSTRAINT FK_F42B5D026B39F0D0 FOREIGN KEY (id_news_id) REFERENCES news (id)');
        $this->addSql('ALTER TABLE commentaire_news ADD CONSTRAINT FK_F42B5D02A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9C6CCBBA04 FOREIGN KEY (id_coach_id) REFERENCES coach (id)');
        $this->addSql('ALTER TABLE gamer ADD CONSTRAINT FK_88241BA7BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE historique_achat ADD CONSTRAINT FK_68295E257F984D83 FOREIGN KEY (id_gamer_id) REFERENCES gamer (id)');
        $this->addSql('ALTER TABLE historique_achat ADD CONSTRAINT FK_68295E25C29D63C1 FOREIGN KEY (idproduit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE membre ADD CONSTRAINT FK_F6B4FB297F984D83 FOREIGN KEY (id_gamer_id) REFERENCES gamer (id)');
        $this->addSql('ALTER TABLE membre ADD CONSTRAINT FK_F6B4FB29F7F171DE FOREIGN KEY (id_team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE membre_groupe ADD CONSTRAINT FK_9EB01998FA7089AB FOREIGN KEY (id_groupe_id) REFERENCES groupe (id)');
        $this->addSql('ALTER TABLE membre_groupe ADD CONSTRAINT FK_9EB019987F984D83 FOREIGN KEY (id_gamer_id) REFERENCES gamer (id)');
        $this->addSql('ALTER TABLE news ADD CONSTRAINT FK_1DD3995032B700A2 FOREIGN KEY (id_jeux_id) REFERENCES jeux (id)');
        $this->addSql('ALTER TABLE planning ADD CONSTRAINT FK_D499BFF67F984D83 FOREIGN KEY (id_gamer_id) REFERENCES gamer (id)');
        $this->addSql('ALTER TABLE planning ADD CONSTRAINT FK_D499BFF66CCBBA04 FOREIGN KEY (id_coach_id) REFERENCES coach (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DFA7089AB FOREIGN KEY (id_groupe_id) REFERENCES groupe (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC279F34925F FOREIGN KEY (id_categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE review_jeux ADD CONSTRAINT FK_D5E1F81532B700A2 FOREIGN KEY (id_jeux_id) REFERENCES jeux (id)');
        $this->addSql('ALTER TABLE review_jeux ADD CONSTRAINT FK_D5E1F8157F984D83 FOREIGN KEY (id_gamer_id) REFERENCES gamer (id)');
        $this->addSql('ALTER TABLE user_courses ADD CONSTRAINT FK_F1A844467F984D83 FOREIGN KEY (id_gamer_id) REFERENCES gamer (id)');
        $this->addSql('ALTER TABLE user_courses ADD CONSTRAINT FK_F1A844462E149425 FOREIGN KEY (id_cours_id) REFERENCES cours (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classement DROP FOREIGN KEY FK_55EE9D6D1F409EBB');
        $this->addSql('ALTER TABLE classement DROP FOREIGN KEY FK_55EE9D6DF7F171DE');
        $this->addSql('ALTER TABLE coach DROP FOREIGN KEY FK_3F596DCCBF396750');
        $this->addSql('ALTER TABLE commentaire_news DROP FOREIGN KEY FK_F42B5D026B39F0D0');
        $this->addSql('ALTER TABLE commentaire_news DROP FOREIGN KEY FK_F42B5D02A76ED395');
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9C6CCBBA04');
        $this->addSql('ALTER TABLE gamer DROP FOREIGN KEY FK_88241BA7BF396750');
        $this->addSql('ALTER TABLE historique_achat DROP FOREIGN KEY FK_68295E257F984D83');
        $this->addSql('ALTER TABLE historique_achat DROP FOREIGN KEY FK_68295E25C29D63C1');
        $this->addSql('ALTER TABLE membre DROP FOREIGN KEY FK_F6B4FB297F984D83');
        $this->addSql('ALTER TABLE membre DROP FOREIGN KEY FK_F6B4FB29F7F171DE');
        $this->addSql('ALTER TABLE membre_groupe DROP FOREIGN KEY FK_9EB01998FA7089AB');
        $this->addSql('ALTER TABLE membre_groupe DROP FOREIGN KEY FK_9EB019987F984D83');
        $this->addSql('ALTER TABLE news DROP FOREIGN KEY FK_1DD3995032B700A2');
        $this->addSql('ALTER TABLE planning DROP FOREIGN KEY FK_D499BFF67F984D83');
        $this->addSql('ALTER TABLE planning DROP FOREIGN KEY FK_D499BFF66CCBBA04');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DFA7089AB');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC279F34925F');
        $this->addSql('ALTER TABLE review_jeux DROP FOREIGN KEY FK_D5E1F81532B700A2');
        $this->addSql('ALTER TABLE review_jeux DROP FOREIGN KEY FK_D5E1F8157F984D83');
        $this->addSql('ALTER TABLE user_courses DROP FOREIGN KEY FK_F1A844467F984D83');
        $this->addSql('ALTER TABLE user_courses DROP FOREIGN KEY FK_F1A844462E149425');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE classement');
        $this->addSql('DROP TABLE coach');
        $this->addSql('DROP TABLE commentaire_news');
        $this->addSql('DROP TABLE cours');
        $this->addSql('DROP TABLE gamer');
        $this->addSql('DROP TABLE groupe');
        $this->addSql('DROP TABLE historique_achat');
        $this->addSql('DROP TABLE historique_point');
        $this->addSql('DROP TABLE jeux');
        $this->addSql('DROP TABLE membre');
        $this->addSql('DROP TABLE membre_groupe');
        $this->addSql('DROP TABLE news');
        $this->addSql('DROP TABLE planning');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE review_jeux');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE tournoi');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_courses');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
