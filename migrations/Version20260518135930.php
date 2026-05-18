<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260518135930 extends AbstractMigration
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
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE appartement');
        $this->addSql('DROP TABLE chambre');
    }
}
