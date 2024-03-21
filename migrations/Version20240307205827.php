<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307205827 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE top50_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE top50 (id INT NOT NULL, style_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, artiste VARCHAR(255) NOT NULL, annee INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_792CCC0DBACD6074 ON top50 (style_id)');
        $this->addSql('ALTER TABLE top50 ADD CONSTRAINT FK_792CCC0DBACD6074 FOREIGN KEY (style_id) REFERENCES style (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE top50_id_seq CASCADE');
        $this->addSql('ALTER TABLE top50 DROP CONSTRAINT FK_792CCC0DBACD6074');
        $this->addSql('DROP TABLE top50');
    }
}
