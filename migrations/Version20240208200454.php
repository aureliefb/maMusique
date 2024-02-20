<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240208200454 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE artiste DROP CONSTRAINT FK_9C07354FBACD6074');
        $this->addSql('ALTER TABLE artiste ADD CONSTRAINT FK_9C07354FBACD6074 FOREIGN KEY (style_id) REFERENCES style (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE festival ADD date_start VARCHAR(10) DEFAULT NULL');
        $this->addSql('ALTER TABLE festival ADD date_end VARCHAR(10) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE artiste DROP CONSTRAINT fk_9c07354fbacd6074');
        $this->addSql('ALTER TABLE artiste ADD CONSTRAINT fk_9c07354fbacd6074 FOREIGN KEY (style_id) REFERENCES style (id) ON UPDATE CASCADE ON DELETE RESTRICT NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE festival DROP date_start');
        $this->addSql('ALTER TABLE festival DROP date_end');
    }
}
