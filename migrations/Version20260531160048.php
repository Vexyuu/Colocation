<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260531160048 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE appartement (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, adresse LONGTEXT NOT NULL, superficie_totale DOUBLE PRECISION NOT NULL, landlord_id INT NOT NULL, INDEX IDX_71A6BD8DD48E7AED (landlord_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE chambre (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, surface DOUBLE PRECISION NOT NULL, appartment_id INT NOT NULL, tenant_id INT DEFAULT NULL, INDEX IDX_C509E4FF2714DC20 (appartment_id), UNIQUE INDEX UNIQ_C509E4FF9033212A (tenant_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE chores (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, day_of_the_week VARCHAR(255) NOT NULL, chore_status VARCHAR(255) NOT NULL, appartment_id INT NOT NULL, assigned_to_id INT NOT NULL, INDEX IDX_735604D42714DC20 (appartment_id), INDEX IDX_735604D4F4BD7827 (assigned_to_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE facture (id INT AUTO_INCREMENT NOT NULL, type_of_charge VARCHAR(255) NOT NULL, total_amount DOUBLE PRECISION NOT NULL, bill_date DATE NOT NULL, appartment_id INT NOT NULL, INDEX IDX_FE8664102714DC20 (appartment_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, content LONGTEXT NOT NULL, date_sent DATETIME NOT NULL, sender_id INT NOT NULL, receiver_id INT NOT NULL, INDEX IDX_B6BD307FF624B39D (sender_id), INDEX IDX_B6BD307FCD53EDB6 (receiver_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE quittance (id INT AUTO_INCREMENT NOT NULL, rent_without_charges DOUBLE PRECISION NOT NULL, rent_with_charges_percent DOUBLE PRECISION NOT NULL, transmission_date DATE NOT NULL, payment_status VARCHAR(255) NOT NULL, tenant_id INT NOT NULL, bill_ref_id INT DEFAULT NULL, INDEX IDX_D57587DD9033212A (tenant_id), INDEX IDX_D57587DD36360FBF (bill_ref_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, password_reset_token VARCHAR(255) DEFAULT NULL, password_reset_token_expires_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE appartement ADD CONSTRAINT FK_71A6BD8DD48E7AED FOREIGN KEY (landlord_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE chambre ADD CONSTRAINT FK_C509E4FF2714DC20 FOREIGN KEY (appartment_id) REFERENCES appartement (id)');
        $this->addSql('ALTER TABLE chambre ADD CONSTRAINT FK_C509E4FF9033212A FOREIGN KEY (tenant_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE chores ADD CONSTRAINT FK_735604D42714DC20 FOREIGN KEY (appartment_id) REFERENCES appartement (id)');
        $this->addSql('ALTER TABLE chores ADD CONSTRAINT FK_735604D4F4BD7827 FOREIGN KEY (assigned_to_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE8664102714DC20 FOREIGN KEY (appartment_id) REFERENCES appartement (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FCD53EDB6 FOREIGN KEY (receiver_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE quittance ADD CONSTRAINT FK_D57587DD9033212A FOREIGN KEY (tenant_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE quittance ADD CONSTRAINT FK_D57587DD36360FBF FOREIGN KEY (bill_ref_id) REFERENCES facture (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appartement DROP FOREIGN KEY FK_71A6BD8DD48E7AED');
        $this->addSql('ALTER TABLE chambre DROP FOREIGN KEY FK_C509E4FF2714DC20');
        $this->addSql('ALTER TABLE chambre DROP FOREIGN KEY FK_C509E4FF9033212A');
        $this->addSql('ALTER TABLE chores DROP FOREIGN KEY FK_735604D42714DC20');
        $this->addSql('ALTER TABLE chores DROP FOREIGN KEY FK_735604D4F4BD7827');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE8664102714DC20');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF624B39D');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FCD53EDB6');
        $this->addSql('ALTER TABLE quittance DROP FOREIGN KEY FK_D57587DD9033212A');
        $this->addSql('ALTER TABLE quittance DROP FOREIGN KEY FK_D57587DD36360FBF');
        $this->addSql('DROP TABLE appartement');
        $this->addSql('DROP TABLE chambre');
        $this->addSql('DROP TABLE chores');
        $this->addSql('DROP TABLE facture');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE quittance');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
