<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220420170942 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE codevalidation (idcode INT AUTO_INCREMENT NOT NULL, code VARCHAR(10) NOT NULL, coderec_mp VARCHAR(15) NOT NULL, email VARCHAR(15) NOT NULL, INDEX email (email), PRIMARY KEY(idcode)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE endroit (id INT AUTO_INCREMENT NOT NULL, region_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, type VARCHAR(255) DEFAULT NULL, description TEXT NOT NULL, longitude DOUBLE PRECISION NOT NULL, latitude DOUBLE PRECISION NOT NULL, numero INT DEFAULT NULL, image LONGBLOB DEFAULT NULL, INDEX test (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement (idevent INT AUTO_INCREMENT NOT NULL, id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, lieu VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, capacite INT NOT NULL, INDEX id (id), PRIMARY KEY(idevent)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hebergement (idheberg INT AUTO_INCREMENT NOT NULL, address VARCHAR(255) NOT NULL, photo VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(idheberg)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (idrec INT AUTO_INCREMENT NOT NULL, idevent INT DEFAULT NULL, idtransport INT DEFAULT NULL, id INT DEFAULT NULL, idheberg INT DEFAULT NULL, contenu VARCHAR(255) NOT NULL, date DATE NOT NULL, etat TINYINT(1) NOT NULL, INDEX idheberg (idheberg), INDEX idtransport (idtransport), INDEX id (id), INDEX idevent (idevent), PRIMARY KEY(idrec)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description MEDIUMTEXT DEFAULT NULL, photo LONGBLOB DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reser_evenement (id_reser INT AUTO_INCREMENT NOT NULL, id INT DEFAULT NULL, idevent INT DEFAULT NULL, Date_reservation DATE NOT NULL, INDEX idevent (idevent), INDEX id (id), PRIMARY KEY(id_reser)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transport (idtransport INT AUTO_INCREMENT NOT NULL, address VARCHAR(255) NOT NULL, photo VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(idtransport)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, passwd VARCHAR(255) NOT NULL, country VARCHAR(255) DEFAULT NULL, role VARCHAR(255) NOT NULL, photo VARCHAR(1000) DEFAULT NULL, num_tel VARCHAR(255) DEFAULT NULL, valide TINYINT(1) NOT NULL, etat TINYINT(1) DEFAULT NULL, INDEX email (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE endroit ADD CONSTRAINT FK_7B44825A98260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681EBF396750 FOREIGN KEY (id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404EDAB66BE FOREIGN KEY (idevent) REFERENCES evenement (idevent)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE60640418B088DB FOREIGN KEY (idtransport) REFERENCES transport (idtransport)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404BF396750 FOREIGN KEY (id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE60640471E6A0C9 FOREIGN KEY (idheberg) REFERENCES hebergement (idheberg)');
        $this->addSql('ALTER TABLE reser_evenement ADD CONSTRAINT FK_C5C6C88BBF396750 FOREIGN KEY (id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reser_evenement ADD CONSTRAINT FK_C5C6C88BEDAB66BE FOREIGN KEY (idevent) REFERENCES evenement (idevent)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404EDAB66BE');
        $this->addSql('ALTER TABLE reser_evenement DROP FOREIGN KEY FK_C5C6C88BEDAB66BE');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE60640471E6A0C9');
        $this->addSql('ALTER TABLE endroit DROP FOREIGN KEY FK_7B44825A98260155');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE60640418B088DB');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681EBF396750');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404BF396750');
        $this->addSql('ALTER TABLE reser_evenement DROP FOREIGN KEY FK_C5C6C88BBF396750');
        $this->addSql('DROP TABLE codevalidation');
        $this->addSql('DROP TABLE endroit');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE hebergement');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE reser_evenement');
        $this->addSql('DROP TABLE transport');
        $this->addSql('DROP TABLE user');
    }
}
