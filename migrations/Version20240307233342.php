<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307233342 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_369ECA32C5452A49 ON fournisseur (numdetel)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_369ECA32E7927C74 ON fournisseur (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_369ECA32C5452A49 ON fournisseur');
        $this->addSql('DROP INDEX UNIQ_369ECA32E7927C74 ON fournisseur');
    }
}
