<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191028153110 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE stat_rm_jour (id INT AUTO_INCREMENT NOT NULL, jour DATETIME NOT NULL, commission DOUBLE PRECISION NOT NULL, forfait DOUBLE PRECISION NOT NULL, ca_total DOUBLE PRECISION NOT NULL, ca_variable DOUBLE PRECISION NOT NULL, ca_forfait DOUBLE PRECISION NOT NULL, mode_livraison DOUBLE PRECISION NOT NULL, mode_ae DOUBLE PRECISION NOT NULL, commande_midi DOUBLE PRECISION NOT NULL, commande_soir DOUBLE PRECISION NOT NULL, support_rm DOUBLE PRECISION NOT NULL, support_smart DOUBLE PRECISION NOT NULL, support_vit DOUBLE PRECISION NOT NULL, support_autre DOUBLE PRECISION NOT NULL, client_connecte DOUBLE PRECISION NOT NULL, client_express DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stat_rm_mois (id INT AUTO_INCREMENT NOT NULL, jour DATETIME NOT NULL, commission DOUBLE PRECISION NOT NULL, forfait DOUBLE PRECISION NOT NULL, ca_total DOUBLE PRECISION NOT NULL, ca_variable DOUBLE PRECISION NOT NULL, ca_forfait DOUBLE PRECISION NOT NULL, mode_livraison DOUBLE PRECISION NOT NULL, mode_ae DOUBLE PRECISION NOT NULL, commande_midi DOUBLE PRECISION NOT NULL, commande_soir DOUBLE PRECISION NOT NULL, support_rm DOUBLE PRECISION NOT NULL, support_smart DOUBLE PRECISION NOT NULL, support_vit DOUBLE PRECISION NOT NULL, support_autre DOUBLE PRECISION NOT NULL, client_connecte DOUBLE PRECISION NOT NULL, client_express DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stat_rm_annee (id INT AUTO_INCREMENT NOT NULL, annee INT NOT NULL, commission DOUBLE PRECISION NOT NULL, forfait DOUBLE PRECISION NOT NULL, ca_total DOUBLE PRECISION NOT NULL, ca_variable DOUBLE PRECISION NOT NULL, ca_forfait DOUBLE PRECISION NOT NULL, mode_livraison DOUBLE PRECISION NOT NULL, mode_ae DOUBLE PRECISION NOT NULL, commande_midi DOUBLE PRECISION NOT NULL, commande_soir DOUBLE PRECISION NOT NULL, support_rm DOUBLE PRECISION NOT NULL, support_smart DOUBLE PRECISION NOT NULL, support_vit DOUBLE PRECISION NOT NULL, support_autre DOUBLE PRECISION NOT NULL, client_connecte DOUBLE PRECISION NOT NULL, client_express DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE stat_rm_jour');
        $this->addSql('DROP TABLE stat_rm_mois');
        $this->addSql('DROP TABLE stat_rm_annee');
    }
}
