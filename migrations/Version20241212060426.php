<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241212060426 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE annonce (id INT AUTO_INCREMENT NOT NULL, poste_id INT NOT NULL, recruteur_id INT NOT NULL, consultant_id INT DEFAULT NULL, titre VARCHAR(100) NOT NULL, typecontrat VARCHAR(20) NOT NULL, ville VARCHAR(60) NOT NULL, datedebut DATE NOT NULL, datefin DATE DEFAULT NULL, nombreheures INT NOT NULL, salaire INT NOT NULL, description LONGTEXT NOT NULL, dateajout DATETIME NOT NULL, validation TINYINT(1) NOT NULL, INDEX IDX_F65593E5A0905086 (poste_id), INDEX IDX_F65593E5BB0859F1 (recruteur_id), INDEX IDX_F65593E544F779A2 (consultant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE candidat (id INT AUTO_INCREMENT NOT NULL, candidat_user_id INT NOT NULL, consultant_id INT DEFAULT NULL, cv VARCHAR(255) DEFAULT NULL, INDEX IDX_6AB5B471FE63257F (candidat_user_id), INDEX IDX_6AB5B47144F779A2 (consultant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE candidature (id INT AUTO_INCREMENT NOT NULL, annonce_id INT NOT NULL, candidat_id INT NOT NULL, consultant_approval_id INT DEFAULT NULL, etat VARCHAR(10) NOT NULL, INDEX IDX_E33BD3B88805AB2F (annonce_id), INDEX IDX_E33BD3B88D0EB82 (candidat_id), INDEX IDX_E33BD3B8CC233509 (consultant_approval_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE poste (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(80) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recruteur (id INT AUTO_INCREMENT NOT NULL, recruteur_user_id INT NOT NULL, consultant_id INT DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, code_postal INT DEFAULT NULL, ville VARCHAR(255) DEFAULT NULL, INDEX IDX_2BD3678CBB18A082 (recruteur_user_id), INDEX IDX_2BD3678C44F779A2 (consultant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, role VARCHAR(40) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5A0905086 FOREIGN KEY (poste_id) REFERENCES poste (id)');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5BB0859F1 FOREIGN KEY (recruteur_id) REFERENCES recruteur (id)');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E544F779A2 FOREIGN KEY (consultant_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE candidat ADD CONSTRAINT FK_6AB5B471FE63257F FOREIGN KEY (candidat_user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE candidat ADD CONSTRAINT FK_6AB5B47144F779A2 FOREIGN KEY (consultant_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B88805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id)');
        $this->addSql('ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B88D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id)');
        $this->addSql('ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B8CC233509 FOREIGN KEY (consultant_approval_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE recruteur ADD CONSTRAINT FK_2BD3678CBB18A082 FOREIGN KEY (recruteur_user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE recruteur ADD CONSTRAINT FK_2BD3678C44F779A2 FOREIGN KEY (consultant_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5A0905086');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5BB0859F1');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E544F779A2');
        $this->addSql('ALTER TABLE candidat DROP FOREIGN KEY FK_6AB5B471FE63257F');
        $this->addSql('ALTER TABLE candidat DROP FOREIGN KEY FK_6AB5B47144F779A2');
        $this->addSql('ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B88805AB2F');
        $this->addSql('ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B88D0EB82');
        $this->addSql('ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B8CC233509');
        $this->addSql('ALTER TABLE recruteur DROP FOREIGN KEY FK_2BD3678CBB18A082');
        $this->addSql('ALTER TABLE recruteur DROP FOREIGN KEY FK_2BD3678C44F779A2');
        $this->addSql('DROP TABLE annonce');
        $this->addSql('DROP TABLE candidat');
        $this->addSql('DROP TABLE candidature');
        $this->addSql('DROP TABLE poste');
        $this->addSql('DROP TABLE recruteur');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
