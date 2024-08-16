<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240814163114 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE import_file ADD supplier_id INT NOT NULL');
        $this->addSql('ALTER TABLE import_file ADD CONSTRAINT FK_61B3D8902ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('CREATE INDEX IDX_61B3D8902ADD6D8C ON import_file (supplier_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE import_file DROP FOREIGN KEY FK_61B3D8902ADD6D8C');
        $this->addSql('DROP INDEX IDX_61B3D8902ADD6D8C ON import_file');
        $this->addSql('ALTER TABLE import_file DROP supplier_id');
    }
}
