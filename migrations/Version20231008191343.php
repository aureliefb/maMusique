<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231008191343 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE artiste ADD style_id INT NOT NULL');
        $this->addSql('ALTER TABLE artiste ADD pays_id INT NOT NULL');
        $this->addSql('ALTER TABLE artiste ADD CONSTRAINT FK_9C07354FBACD6074 FOREIGN KEY (style_id) REFERENCES style (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE artiste ADD CONSTRAINT FK_9C07354FA6E44244 FOREIGN KEY (pays_id) REFERENCES pays (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_9C07354FBACD6074 ON artiste (style_id)');
        $this->addSql('CREATE INDEX IDX_9C07354FA6E44244 ON artiste (pays_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE artiste DROP CONSTRAINT FK_9C07354FBACD6074');
        $this->addSql('ALTER TABLE artiste DROP CONSTRAINT FK_9C07354FA6E44244');
        $this->addSql('DROP INDEX IDX_9C07354FBACD6074');
        $this->addSql('DROP INDEX IDX_9C07354FA6E44244');
        $this->addSql('ALTER TABLE artiste DROP style_id');
        $this->addSql('ALTER TABLE artiste DROP pays_id');
    }
}
