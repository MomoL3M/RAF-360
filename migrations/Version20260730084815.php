<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260730084815 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE action ADD entreprise_id INT NOT NULL');
        $this->addSql('ALTER TABLE action ADD CONSTRAINT FK_47CC8C92A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id) NOT DEFERRABLE');
        $this->addSql('CREATE INDEX IDX_47CC8C92A4AEAFEA ON action (entreprise_id)');
        $this->addSql('ALTER TABLE document ADD entreprise_id INT NOT NULL');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id) NOT DEFERRABLE');
        $this->addSql('CREATE INDEX IDX_D8698A76A4AEAFEA ON document (entreprise_id)');
        $this->addSql('ALTER TABLE echeance ADD entreprise_id INT NOT NULL');
        $this->addSql('ALTER TABLE echeance ADD CONSTRAINT FK_40D9893BA4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id) NOT DEFERRABLE');
        $this->addSql('CREATE INDEX IDX_40D9893BA4AEAFEA ON echeance (entreprise_id)');
        $this->addSql('ALTER TABLE facture ADD entreprise_id INT NOT NULL');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE866410A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id) NOT DEFERRABLE');
        $this->addSql('CREATE INDEX IDX_FE866410A4AEAFEA ON facture (entreprise_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE action DROP CONSTRAINT FK_47CC8C92A4AEAFEA');
        $this->addSql('DROP INDEX IDX_47CC8C92A4AEAFEA');
        $this->addSql('ALTER TABLE action DROP entreprise_id');
        $this->addSql('ALTER TABLE document DROP CONSTRAINT FK_D8698A76A4AEAFEA');
        $this->addSql('DROP INDEX IDX_D8698A76A4AEAFEA');
        $this->addSql('ALTER TABLE document DROP entreprise_id');
        $this->addSql('ALTER TABLE echeance DROP CONSTRAINT FK_40D9893BA4AEAFEA');
        $this->addSql('DROP INDEX IDX_40D9893BA4AEAFEA');
        $this->addSql('ALTER TABLE echeance DROP entreprise_id');
        $this->addSql('ALTER TABLE facture DROP CONSTRAINT FK_FE866410A4AEAFEA');
        $this->addSql('DROP INDEX IDX_FE866410A4AEAFEA');
        $this->addSql('ALTER TABLE facture DROP entreprise_id');
    }
}
