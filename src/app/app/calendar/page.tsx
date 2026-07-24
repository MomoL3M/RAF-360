import { C } from "@/lib/tokens";
import { frDate, isOverdue } from "@/lib/format";
import { ECHEANCES, statutColor } from "@/data/demo";
import { SectionTitle, Badge } from "@/components/ui";
import AiInsight from "@/components/AiInsight";

export default function Calendar() {
  return (
    <>
      <SectionTitle
        title="Calendrier des obligations"
        sub="Trié de la date la plus proche à la plus lointaine — chaque règle est datée et sourcée"
      />
      <AiInsight
        sev={C.navy2}
        kind="Anticipation"
        title="1er acompte d'IS : ≈ 4 250 € estimés"
        body="Estimation calculée à partir de votre dernier IS connu (1/4 de l'IS N-1). Le copilote affinera le montant réel dès réception de la liasse. Prévoir la provision avant le 15 mars."
        src="Base : impôt sur les sociétés N-1 (démo) · à confirmer avec votre expert-comptable"
      />
      <div className="card mt16" style={{ padding: 0 }}>
        {ECHEANCES.map((e, i) => {
          const od = isOverdue(e.iso);
          const col = od ? C.red : statutColor[e.statut];
          const [d, m] = frDate(e.iso).split(" ");
          return (
            <div className="list-row" key={i} style={od ? { background: C.redl } : undefined}>
              <div style={{ display: "flex", alignItems: "center", gap: 14 }}>
                <div
                  style={{
                    width: 48,
                    height: 48,
                    borderRadius: 10,
                    background: col + "18",
                    display: "flex",
                    flexDirection: "column",
                    alignItems: "center",
                    justifyContent: "center",
                  }}
                >
                  <span style={{ fontSize: 16, fontWeight: 800, color: col }}>{d}</span>
                  <span style={{ fontSize: 10, color: C.slate }}>{m || ""}</span>
                </div>
                <div>
                  <div style={{ fontSize: 15, color: C.ink, fontWeight: 600 }}>
                    {e.t}
                    {e.montant && (
                      <span
                        className="chip"
                        style={{
                          marginLeft: 6,
                          color: e.mt === "réel" ? C.slate : C.gold,
                          borderColor: e.mt === "réel" ? C.line : C.gold + "55",
                        }}
                      >
                        {e.montant} · {e.mt}
                      </span>
                    )}
                  </div>
                  <div style={{ fontSize: 12, color: C.slate }}>
                    Priorité {e.prio.toLowerCase()}
                    {od && (
                      <>
                        {" "}
                        · <b style={{ color: C.red }}>en retard</b>
                      </>
                    )}
                  </div>
                </div>
              </div>
              <div style={{ display: "flex", gap: 12, alignItems: "center" }}>
                <Badge color={col}>{od ? "En retard" : e.statut}</Badge>
                <button className="btn btn-ghost btn-sm">Préparer</button>
              </div>
            </div>
          );
        })}
      </div>
    </>
  );
}
