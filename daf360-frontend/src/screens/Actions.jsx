import { C } from "../lib/tokens.js";
import { ACTIONS, statutColor, statutLabel } from "../data/demo.js";
import { SectionTitle, Badge } from "../components/ui.jsx";
import AiInsight from "../components/AiInsight.jsx";

export default function Actions() {
  return (
    <>
      <SectionTitle title="Centre d'actions" sub="Une vue unique de tout ce qui requiert votre attention — priorisé par le copilote" />
      <AiInsight
        sev={C.gold}
        kind="Anticipation"
        title="2 actions peuvent en éviter 4 autres"
        body={<>Traiter le <b>doublon fournisseur ACME</b> et les <b>3 pièces TVA manquantes</b> avant le 12 fév. sécurise la déclaration et évite une régularisation. Le copilote a déjà préparé les brouillons.</>}
        cta={[{ t: "Préparer la TVA", primary: true }, { t: "Voir le doublon" }]}
      />
      <div className="card mt16" style={{ padding: 0 }}>
        {ACTIONS.map((a, i) => (
          <div className="list-row" key={i}>
            <div style={{ display: "flex", alignItems: "center", gap: 14 }}>
              <span className="dot" style={{ background: statutColor[a.statut] }} />
              <span style={{ fontSize: 14, color: C.ink }}>{a.t}</span>
            </div>
            <div style={{ display: "flex", gap: 12, alignItems: "center" }}>
              <Badge color={statutColor[a.statut]}>{statutLabel[a.statut]}</Badge>
              <span style={{ fontSize: 12, color: C.slate, width: 60, textAlign: "right" }}>{a.who}</span>
            </div>
          </div>
        ))}
      </div>
    </>
  );
}
