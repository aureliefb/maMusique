<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231008192038 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE festival ADD lieu_id INT NOT NULL');
        $this->addSql('ALTER TABLE festival ADD CONSTRAINT FK_57CF7896AB213CC FOREIGN KEY (lieu_id) REFERENCES lieu (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_57CF7896AB213CC ON festival (lieu_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE festival DROP CONSTRAINT FK_57CF7896AB213CC');
        $this->addSql('DROP INDEX IDX_57CF7896AB213CC');
        $this->addSql('ALTER TABLE festival DROP lieu_id');
    }
}
