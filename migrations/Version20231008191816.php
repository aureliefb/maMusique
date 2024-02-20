<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231008191816 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE concert ADD artiste_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE concert ADD lieu_id INT NOT NULL');
        $this->addSql('ALTER TABLE concert ADD CONSTRAINT FK_D57C02D221D25844 FOREIGN KEY (artiste_id) REFERENCES artiste (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE concert ADD CONSTRAINT FK_D57C02D26AB213CC FOREIGN KEY (lieu_id) REFERENCES lieu (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_D57C02D221D25844 ON concert (artiste_id)');
        $this->addSql('CREATE INDEX IDX_D57C02D26AB213CC ON concert (lieu_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE concert DROP CONSTRAINT FK_D57C02D221D25844');
        $this->addSql('ALTER TABLE concert DROP CONSTRAINT FK_D57C02D26AB213CC');
        $this->addSql('DROP INDEX IDX_D57C02D221D25844');
        $this->addSql('DROP INDEX IDX_D57C02D26AB213CC');
        $this->addSql('ALTER TABLE concert DROP artiste_id');
        $this->addSql('ALTER TABLE concert DROP lieu_id');
    }
}
