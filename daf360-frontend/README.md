# DAF 360 — Front-end

Portage React (Vite) de la maquette DAF 360, avec la couche Copilote IA intégrée sur chaque écran.

## Démarrer

Prérequis : **Node.js 18+**.

```bash
npm install
npm run dev      # http://localhost:5173
```

Build de production :

```bash
npm run build
npm run preview
```

## Travailler avec Claude Code (VS Code)

1. Ouvrir ce dossier dans VS Code.
2. Installer l'extension **Claude Code** (éditeur : Anthropic) depuis les Extensions (Cmd/Ctrl+Shift+X).
3. Lancer Claude Code (icône ✱ ou palette de commandes). Il lira **`CLAUDE.md`** comme contexte du projet.
4. Exemple de première consigne :
   > « Lis CLAUDE.md, puis extrais les styles inline de l'écran Trésorerie vers des classes dans tokens.css, sans changer le rendu. »

Doc officielle : https://code.claude.com/docs/en/vs-code

## Structure

Voir `CLAUDE.md` pour l'arborescence détaillée, la charte graphique et l'état des écrans.

Toutes les données sont **fictives** (`src/data/demo.js`) — aucune connexion réelle.
