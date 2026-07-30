#!/bin/sh
# Test de fumée post-déploiement (§23.2).
#
# Vérifie que les pages qui comptent répondent ET contiennent ce qu'elles doivent
# contenir. Un simple code 200 ne suffit pas : une page peut répondre 200 en affichant
# une erreur. Chaque échec sort en code non nul → alerte immédiate et rollback.
#
# Usage : ./bin/test-de-fumee.sh [url_de_base]
#         BASE_URL=https://raf360.fr ./bin/test-de-fumee.sh
#         FUMEE_CERT_AUTOSIGNE=1 ./bin/test-de-fumee.sh https://localhost
#
# FUMEE_CERT_AUTOSIGNE n'est destiné qu'aux environnements locaux à certificat
# auto-signé. En production, la validation du certificat fait partie du test.

set -u

BASE="${1:-${BASE_URL:-http://127.0.0.1:8000}}"
INSECURE=''
[ "${FUMEE_CERT_AUTOSIGNE:-0}" = "1" ] && INSECURE='-k'
ECHECS=0
TOTAL=0

# chemin|code attendu|fragment attendu dans le corps
CAS="
/health|200|\"status\":\"ok\"
/|200|RAF360
/produit|200|<h1
/tarifs|200|<h1
/contact|200|<form
/diagnostic|200|<form
/mentions-legales|200|Lindbergh Formation
/politique-de-confidentialite|200|Vos droits
/sitemap.xml|200|<urlset
/robots.txt|200|Sitemap:
/llms.txt|200|RAF360
/cette-page-nexiste-pas|404|
"

verifier() {
    chemin="$1"
    attendu="$2"
    fragment="$3"
    TOTAL=$((TOTAL + 1))

    # Pas de « || echo 000 » ici : curl écrit déjà 000 en cas d'échec, et le cumul des
    # deux produirait un code illisible du genre « 000000 ».
    reponse="$(curl -sS $INSECURE -o /tmp/fumee-corps -w '%{http_code}' \
        -H 'Accept-Encoding: identity' --max-time 15 "$BASE$chemin" 2>/dev/null)"
    [ -n "$reponse" ] || reponse='000'

    if [ "$reponse" != "$attendu" ]; then
        echo "ÉCHEC  $chemin — code $reponse (attendu $attendu)"
        ECHECS=$((ECHECS + 1))
        return
    fi

    if [ -n "$fragment" ] && ! grep -qF "$fragment" /tmp/fumee-corps; then
        echo "ÉCHEC  $chemin — code $attendu mais « $fragment » est absent du contenu"
        ECHECS=$((ECHECS + 1))
        return
    fi

    echo "OK     $chemin ($reponse)"
}

echo "Test de fumée sur $BASE"
echo "---"

# IFS sur le retour à la ligne pour parcourir les cas ligne par ligne.
OLD_IFS="$IFS"
IFS='
'
for ligne in $CAS; do
    [ -z "$ligne" ] && continue
    IFS='|' read -r chemin code fragment <<CAS_LIGNE
$ligne
CAS_LIGNE
    IFS='
'
    verifier "$chemin" "$code" "$fragment"
done
IFS="$OLD_IFS"

rm -f /tmp/fumee-corps
echo "---"

if [ "$ECHECS" -gt 0 ]; then
    echo "$ECHECS échec(s) sur $TOTAL — le déploiement doit être ANNULÉ (voir docs/exploitation.md §5)."
    exit 1
fi

echo "$TOTAL vérifications passées."
