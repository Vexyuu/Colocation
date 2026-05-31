<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260531172443 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE annonce (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, price DOUBLE PRECISION NOT NULL, surface DOUBLE PRECISION NOT NULL, photo_filename VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, appartement_id INT NOT NULL, author_id INT NOT NULL, INDEX IDX_F65593E5E1729BBA (appartement_id), INDEX IDX_F65593E5F675F31B (author_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5E1729BBA FOREIGN KEY (appartement_id) REFERENCES appartement (id)');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
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
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5E1729BBA');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5F675F31B');
        $this->addSql('DROP TABLE annonce');
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
    }
}
