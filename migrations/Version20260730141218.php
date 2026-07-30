<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Horodatages du cycle de vie des comptes : ils rendent APPLICABLES les durées de
 * conservation RGPD (§17.1) — création, dernière connexion, anonymisation.
 */
final class Version20260730141218 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Utilisateur : ajoute cree_le, derniere_connexion et anonymise_le (durées de conservation RGPD)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE utilisateur ADD derniere_connexion TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD anonymise_le TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');

        // cree_le est NOT NULL : on l'ajoute nullable, on remplit les comptes existants,
        // puis on pose la contrainte. Sinon la migration casse dès qu'une ligne existe.
        $this->addSql('ALTER TABLE utilisateur ADD cree_le TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('UPDATE utilisateur SET cree_le = CURRENT_TIMESTAMP WHERE cree_le IS NULL');
        $this->addSql('ALTER TABLE utilisateur ALTER COLUMN cree_le SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE utilisateur DROP cree_le');
        $this->addSql('ALTER TABLE utilisateur DROP derniere_connexion');
        $this->addSql('ALTER TABLE utilisateur DROP anonymise_le');
    }
}
