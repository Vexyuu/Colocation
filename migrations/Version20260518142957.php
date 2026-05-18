<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260518142957 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE appartement (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, adresse CLOB NOT NULL, superficie_totale DOUBLE PRECISION NOT NULL, landlord_id INTEGER NOT NULL, CONSTRAINT FK_71A6BD8DD48E7AED FOREIGN KEY (landlord_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_71A6BD8DD48E7AED ON appartement (landlord_id)');
        $this->addSql('CREATE TABLE chambre (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, surface DOUBLE PRECISION NOT NULL, appartment_id INTEGER NOT NULL, tenant_id INTEGER DEFAULT NULL, CONSTRAINT FK_C509E4FF2714DC20 FOREIGN KEY (appartment_id) REFERENCES appartement (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C509E4FF9033212A FOREIGN KEY (tenant_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_C509E4FF2714DC20 ON chambre (appartment_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C509E4FF9033212A ON chambre (tenant_id)');
        $this->addSql('CREATE TABLE chores (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, day_of_the_week VARCHAR(255) NOT NULL, chore_status VARCHAR(255) NOT NULL, appartment_id INTEGER NOT NULL, assigned_to_id INTEGER NOT NULL, CONSTRAINT FK_735604D42714DC20 FOREIGN KEY (appartment_id) REFERENCES appartement (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_735604D4F4BD7827 FOREIGN KEY (assigned_to_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_735604D42714DC20 ON chores (appartment_id)');
        $this->addSql('CREATE INDEX IDX_735604D4F4BD7827 ON chores (assigned_to_id)');
        $this->addSql('CREATE TABLE facture (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type_of_charge VARCHAR(255) NOT NULL, total_amount DOUBLE PRECISION NOT NULL, bill_date DATE NOT NULL, appartment_id INTEGER NOT NULL, CONSTRAINT FK_FE8664102714DC20 FOREIGN KEY (appartment_id) REFERENCES appartement (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_FE8664102714DC20 ON facture (appartment_id)');
        $this->addSql('CREATE TABLE message (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, content CLOB NOT NULL, date_sent DATETIME NOT NULL, sender_id INTEGER NOT NULL, receiver_id INTEGER NOT NULL, CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_B6BD307FCD53EDB6 FOREIGN KEY (receiver_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_B6BD307FF624B39D ON message (sender_id)');
        $this->addSql('CREATE INDEX IDX_B6BD307FCD53EDB6 ON message (receiver_id)');
        $this->addSql('CREATE TABLE quittance (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, rent_without_charges DOUBLE PRECISION NOT NULL, rent_with_charges_percent DOUBLE PRECISION NOT NULL, transmission_date DATE NOT NULL, payment_status VARCHAR(255) NOT NULL, tenant_id INTEGER NOT NULL, bill_ref_id INTEGER DEFAULT NULL, CONSTRAINT FK_D57587DD9033212A FOREIGN KEY (tenant_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_D57587DD36360FBF FOREIGN KEY (bill_ref_id) REFERENCES facture (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_D57587DD9033212A ON quittance (tenant_id)');
        $this->addSql('CREATE INDEX IDX_D57587DD36360FBF ON quittance (bill_ref_id)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON user (email)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 ON messenger_messages (queue_name, available_at, delivered_at, id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
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
