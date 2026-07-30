#!/bin/sh
# Sauvegarde CHIFFRÉE de la base PostgreSQL (§15, §22).
#
# Produit un fichier <horodatage>.dump.enc chiffré en AES-256, puis vérifie qu'il est
# lisible avant de le déclarer valide. Sort en code non nul à la moindre erreur : c'est
# ce qui permet à la supervision du cron de détecter une sauvegarde qui échoue en
# silence — le pire des cas, puisqu'on ne s'en aperçoit qu'au moment de restaurer.
#
# Prérequis sur la machine qui exécute le script : pg_dump et openssl.
#
# Variables attendues (libpq + passphrase) :
#   PGHOST, PGPORT, PGUSER, PGPASSWORD, PGDATABASE
#   SAUVEGARDE_PASSPHRASE  : secret de chiffrement (coffre de l'hébergeur, JAMAIS le dépôt)
#   SAUVEGARDE_DIR         : dossier de destination (défaut ./var/sauvegardes)
#   SAUVEGARDE_RETENTION   : nombre de jours à conserver localement (défaut 14)
#
# Usage : SAUVEGARDE_PASSPHRASE=… ./bin/sauvegarde-base.sh

set -eu

DESTINATION="${SAUVEGARDE_DIR:-./var/sauvegardes}"
RETENTION="${SAUVEGARDE_RETENTION:-14}"
BASE="${PGDATABASE:-app}"

if [ -z "${SAUVEGARDE_PASSPHRASE:-}" ]; then
    echo "ERREUR : SAUVEGARDE_PASSPHRASE est vide — une sauvegarde non chiffrée n'est pas acceptable." >&2
    exit 1
fi

for outil in pg_dump openssl; do
    command -v "$outil" >/dev/null 2>&1 || { echo "ERREUR : $outil est introuvable." >&2; exit 1; }
done

mkdir -p "$DESTINATION"
HORODATAGE="$(date -u +%Y%m%d-%H%M%S)"
FICHIER="$DESTINATION/$BASE-$HORODATAGE.dump.enc"

# Format « custom » (-Fc) : compressé et restaurable table par table.
# Le chiffrement est enchaîné par tube : le dump en clair ne touche jamais le disque.
pg_dump -Fc --no-owner --no-privileges "$BASE" \
    | openssl enc -aes-256-cbc -pbkdf2 -iter 200000 -salt \
        -pass env:SAUVEGARDE_PASSPHRASE -out "$FICHIER"

# Une sauvegarde qu'on n'a pas relue n'est qu'un fichier : on vérifie qu'elle se
# déchiffre et que pg_restore reconnaît son contenu.
if ! openssl enc -d -aes-256-cbc -pbkdf2 -iter 200000 \
        -pass env:SAUVEGARDE_PASSPHRASE -in "$FICHIER" \
        | pg_restore --list >/dev/null 2>&1; then
    echo "ERREUR : la sauvegarde $FICHIER est illisible — elle est supprimée." >&2
    rm -f "$FICHIER"
    exit 1
fi

TAILLE="$(wc -c < "$FICHIER" | tr -d ' ')"
echo "Sauvegarde vérifiée : $FICHIER ($TAILLE octets)"

# Purge des sauvegardes locales trop anciennes (la copie hors site a sa propre rétention).
find "$DESTINATION" -name "$BASE-*.dump.enc" -type f -mtime "+$RETENTION" -print -delete

echo "Rappel : une sauvegarde qui reste sur le serveur ne protège pas d'une perte du serveur."
echo "La copie hors site (stockage objet chiffré, UE) est obligatoire — voir docs/exploitation.md."
