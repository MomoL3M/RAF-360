#!/bin/sh
# Restauration d'une sauvegarde chiffrée (§22 — PRA).
#
# Opération DESTRUCTRICE : elle écrase le contenu de la base cible. Elle exige donc de
# nommer explicitement la base et de confirmer. À exécuter au moins une fois par
# trimestre sur une base jetable : une sauvegarde non testée n'existe pas.
#
# Variables : PGHOST, PGPORT, PGUSER, PGPASSWORD + SAUVEGARDE_PASSPHRASE
# Usage : SAUVEGARDE_PASSPHRASE=… ./bin/restaurer-base.sh <fichier.dump.enc> <base_cible> [--confirmer]

set -eu

FICHIER="${1:-}"
CIBLE="${2:-}"
CONFIRMATION="${3:-}"

if [ -z "$FICHIER" ] || [ -z "$CIBLE" ]; then
    echo "Usage : $0 <fichier.dump.enc> <base_cible> [--confirmer]" >&2
    exit 1
fi

if [ -z "${SAUVEGARDE_PASSPHRASE:-}" ]; then
    echo "ERREUR : SAUVEGARDE_PASSPHRASE est vide." >&2
    exit 1
fi

[ -f "$FICHIER" ] || { echo "ERREUR : $FICHIER introuvable." >&2; exit 1; }

if [ "$CONFIRMATION" != "--confirmer" ]; then
    echo "Cette opération va ÉCRASER le contenu de la base « $CIBLE »."
    echo "Relancez avec --confirmer si c'est bien l'intention."
    exit 2
fi

# Contrôle préalable : on vérifie que l'archive se déchiffre et se lit AVANT de toucher
# à la base cible. Sans cela, une mauvaise passphrase ou un fichier corrompu pourrait
# commencer par supprimer des objets, puis échouer — le pire des deux mondes.
if ! openssl enc -d -aes-256-cbc -pbkdf2 -iter 200000 \
        -pass env:SAUVEGARDE_PASSPHRASE -in "$FICHIER" \
        | pg_restore --list >/dev/null 2>&1; then
    echo "ERREUR : archive illisible (passphrase incorrecte ou fichier corrompu)." >&2
    echo "La base « $CIBLE » n'a PAS été modifiée." >&2
    exit 1
fi

# Le déchiffrement est enchaîné par tube : le dump en clair ne touche jamais le disque.
# --clean --if-exists rend la restauration rejouable sur une base déjà peuplée.
openssl enc -d -aes-256-cbc -pbkdf2 -iter 200000 \
    -pass env:SAUVEGARDE_PASSPHRASE -in "$FICHIER" \
    | pg_restore --dbname "$CIBLE" --clean --if-exists --no-owner --no-privileges

echo "Restauration terminée dans « $CIBLE »."
echo "Vérifiez les comptages AVANT de rouvrir le service (voir docs/exploitation.md)."
