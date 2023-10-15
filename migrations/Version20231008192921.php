<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231008192921 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE album_artiste (album_id INT NOT NULL, artiste_id INT NOT NULL, PRIMARY KEY(album_id, artiste_id))');
        $this->addSql('CREATE INDEX IDX_C9D0685D1137ABCF ON album_artiste (album_id)');
        $this->addSql('CREATE INDEX IDX_C9D0685D21D25844 ON album_artiste (artiste_id)');
        $this->addSql('ALTER TABLE album_artiste ADD CONSTRAINT FK_C9D0685D1137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE album_artiste ADD CONSTRAINT FK_C9D0685D21D25844 FOREIGN KEY (artiste_id) REFERENCES artiste (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE album_artiste DROP CONSTRAINT FK_C9D0685D1137ABCF');
        $this->addSql('ALTER TABLE album_artiste DROP CONSTRAINT FK_C9D0685D21D25844');
        $this->addSql('DROP TABLE album_artiste');
    }
}
