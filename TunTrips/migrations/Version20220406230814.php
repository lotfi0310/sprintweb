<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220406230814 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE codevalidation DROP FOREIGN KEY codevalidation_ibfk_1');
        $this->addSql('ALTER TABLE codevalidation CHANGE email email VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE codevalidation ADD CONSTRAINT FK_F775824BE7927C74 FOREIGN KEY (email) REFERENCES user (email)');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY reclamation_ibfk_2');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY reclamation_ibfk_4');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY reclamation_ibfk_1');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY reclamation_ibfk_3');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404BF396750 FOREIGN KEY (id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404EDAB66BE FOREIGN KEY (idevent) REFERENCES evenement (idevent)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE60640418B088DB FOREIGN KEY (idtransport) REFERENCES transport (idtransport)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE60640471E6A0C9 FOREIGN KEY (idheberg) REFERENCES hebergement (idheberg)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE codevalidation DROP FOREIGN KEY FK_F775824BE7927C74');
        $this->addSql('ALTER TABLE codevalidation CHANGE email email VARCHAR(50) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE code code VARCHAR(10) NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE codevalidation ADD CONSTRAINT codevalidation_ibfk_1 FOREIGN KEY (email) REFERENCES user (email) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evenement CHANGE address address VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE hebergement CHANGE address address VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE photo photo VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE nom nom VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404BF396750');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404EDAB66BE');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE60640418B088DB');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE60640471E6A0C9');
        $this->addSql('ALTER TABLE reclamation CHANGE contenu contenu VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT reclamation_ibfk_2 FOREIGN KEY (idheberg) REFERENCES hebergement (idheberg) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT reclamation_ibfk_4 FOREIGN KEY (id) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT reclamation_ibfk_1 FOREIGN KEY (idevent) REFERENCES evenement (idevent) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT reclamation_ibfk_3 FOREIGN KEY (idtransport) REFERENCES transport (idtransport) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE transport CHANGE address address VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE photo photo VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE nom nom VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE user CHANGE nom nom VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE prenom prenom VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE passwd passwd VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE country country VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE role role VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE photo photo VARCHAR(1000) DEFAULT \'NULL\' COLLATE `utf8mb4_general_ci`, CHANGE num_tel num_tel VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`');
    }
}
