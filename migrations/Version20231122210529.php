<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231122210529 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game ADD api_key VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE score ADD uuid UUID NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_32993751D17F50A6 ON score (uuid)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_32993751D17F50A6');
        $this->addSql('ALTER TABLE score DROP uuid');
        $this->addSql('ALTER TABLE game DROP api_key');
    }
}
