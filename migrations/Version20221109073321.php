<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221109073321 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE etablissement_categorie (etablissement_id INT NOT NULL, categorie_id INT NOT NULL, INDEX IDX_24A25B5DFF631228 (etablissement_id), INDEX IDX_24A25B5DBCF5E72D (categorie_id), PRIMARY KEY(etablissement_id, categorie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE etablissement_categorie ADD CONSTRAINT FK_24A25B5DFF631228 FOREIGN KEY (etablissement_id) REFERENCES etablissement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE etablissement_categorie ADD CONSTRAINT FK_24A25B5DBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE etablissement ADD ville_id INT NOT NULL, DROP ville, DROP categorie');
        $this->addSql('ALTER TABLE etablissement ADD CONSTRAINT FK_20FD592CA73F0036 FOREIGN KEY (ville_id) REFERENCES ville (id)');
        $this->addSql('CREATE INDEX IDX_20FD592CA73F0036 ON etablissement (ville_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etablissement_categorie DROP FOREIGN KEY FK_24A25B5DFF631228');
        $this->addSql('ALTER TABLE etablissement_categorie DROP FOREIGN KEY FK_24A25B5DBCF5E72D');
        $this->addSql('DROP TABLE etablissement_categorie');
        $this->addSql('ALTER TABLE etablissement DROP FOREIGN KEY FK_20FD592CA73F0036');
        $this->addSql('DROP INDEX IDX_20FD592CA73F0036 ON etablissement');
        $this->addSql('ALTER TABLE etablissement ADD ville VARCHAR(255) NOT NULL, ADD categorie LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', DROP ville_id');
    }
}
