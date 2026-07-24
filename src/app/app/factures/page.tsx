import { C } from "@/lib/tokens";
import { SectionTitle, Donut } from "@/components/ui";
import AiInsight from "@/components/AiInsight";

export default function Factures() {
  const kpis: [string, number, string][] = [
    ["À traiter", 7, C.gold],
    ["Validées", 34, C.green],
    ["Doublons détectés", 1, C.red],
  ];
  return (
    <>
      <SectionTitle title="Factures" sub="Capture, pré-imputation et préparation à la facturation électronique" />
      <AiInsight
        sev={C.gold}
        kind="Anticipation"
        title="2 factures clients à relancer — retard de paiement probable"
        body={
          <>
            BÉTA (9 200 €) et GAMMA (6 480 €) approchent de leur échéance avec un historique de paiement &gt; 45 j. Le
            copilote anticipe un impact sur le BFR de février.
          </>
        }
        scenarios={[
          {
            h: "Relance amiable automatisée",
            pro: ["Préserve la relation", "Rapide, sans coût"],
            con: ["Effet incertain si retard structurel"],
          },
          {
            h: "Escompte pour paiement anticipé",
            pro: ["Accélère l'encaissement", "Sécurise le cash"],
            con: ["Réduit la marge de ~1-2 %"],
          },
        ]}
        reco="Relancer BÉTA aujourd'hui ; proposer un escompte à GAMMA si non payé au 25 fév."
        cta={[{ t: "Lancer les relances", primary: true }]}
      />
      <div className="grid-3" style={{ margin: "16px 0" }}>
        {kpis.map((x, i) => (
          <div className={`card kpi-card ${["kpi-gold", "kpi-green", "kpi-red"][i]}`} key={i}>
            <div style={{ fontSize: 13, color: C.slate }}>{x[0]}</div>
            <div style={{ fontSize: 28, fontWeight: 800, color: x[2] }}>{x[1]}</div>
          </div>
        ))}
      </div>
      <div className="card">
        <div style={{ display: "flex", justifyContent: "space-between", alignItems: "center", flexWrap: "wrap", gap: 12 }}>
          <div>
            <div style={{ fontSize: 15, fontWeight: 700, color: C.navy }}>
              Préparation à la facturation électronique
            </div>
            <div style={{ fontSize: 13, color: C.slate, marginTop: 4 }}>
              Connecté à une plateforme partenaire agréée (PDP). Réception obligatoire au 1er sept. 2026, émission
              TPE/PME au 1er sept. 2027.
            </div>
          </div>
          <Donut pct={65} />
        </div>
      </div>
    </>
  );
}
