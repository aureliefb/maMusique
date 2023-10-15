<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231008192538 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE album_support (album_id INT NOT NULL, support_id INT NOT NULL, PRIMARY KEY(album_id, support_id))');
        $this->addSql('CREATE INDEX IDX_D5D3B6B71137ABCF ON album_support (album_id)');
        $this->addSql('CREATE INDEX IDX_D5D3B6B7315B405 ON album_support (support_id)');
        $this->addSql('ALTER TABLE album_support ADD CONSTRAINT FK_D5D3B6B71137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE album_support ADD CONSTRAINT FK_D5D3B6B7315B405 FOREIGN KEY (support_id) REFERENCES support (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE album_support DROP CONSTRAINT FK_D5D3B6B71137ABCF');
        $this->addSql('ALTER TABLE album_support DROP CONSTRAINT FK_D5D3B6B7315B405');
        $this->addSql('DROP TABLE album_support');
    }
}
