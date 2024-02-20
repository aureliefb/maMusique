<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240220211113 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE concert ALTER artiste_id SET NOT NULL');
        $this->addSql('ALTER TABLE style ADD color VARCHAR(10) NULL');
        //$this->addSql('ALTER TABLE style ADD color_style VARCHAR(255) NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE style DROP color');
        //$this->addSql('ALTER TABLE style DROP color_style');
        $this->addSql('ALTER TABLE concert ALTER artiste_id DROP NOT NULL');
    }
}