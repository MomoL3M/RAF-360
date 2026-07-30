<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260730100108 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alerte_encaissement ADD entreprise_id INT NOT NULL');
        $this->addSql('ALTER TABLE alerte_encaissement ADD CONSTRAINT FK_CEE05CB9A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id) NOT DEFERRABLE');
        $this->addSql('CREATE INDEX IDX_CEE05CB9A4AEAFEA ON alerte_encaissement (entreprise_id)');
        $this->addSql('ALTER TABLE flux_tresorerie ADD entreprise_id INT NOT NULL');
        $this->addSql('ALTER TABLE flux_tresorerie ADD CONSTRAINT FK_B342C743A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id) NOT DEFERRABLE');
        $this->addSql('CREATE INDEX IDX_B342C743A4AEAFEA ON flux_tresorerie (entreprise_id)');
        $this->addSql('ALTER TABLE rendez_vous ADD entreprise_id INT NOT NULL');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0AA4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id) NOT DEFERRABLE');
        $this->addSql('CREATE INDEX IDX_65E8AA0AA4AEAFEA ON rendez_vous (entreprise_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alerte_encaissement DROP CONSTRAINT FK_CEE05CB9A4AEAFEA');
        $this->addSql('DROP INDEX IDX_CEE05CB9A4AEAFEA');
        $this->addSql('ALTER TABLE alerte_encaissement DROP entreprise_id');
        $this->addSql('ALTER TABLE flux_tresorerie DROP CONSTRAINT FK_B342C743A4AEAFEA');
        $this->addSql('DROP INDEX IDX_B342C743A4AEAFEA');
        $this->addSql('ALTER TABLE flux_tresorerie DROP entreprise_id');
        $this->addSql('ALTER TABLE rendez_vous DROP CONSTRAINT FK_65E8AA0AA4AEAFEA');
        $this->addSql('DROP INDEX IDX_65E8AA0AA4AEAFEA');
        $this->addSql('ALTER TABLE rendez_vous DROP entreprise_id');
    }
}
