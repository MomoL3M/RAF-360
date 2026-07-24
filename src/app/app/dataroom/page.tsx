"use client";

import { useState } from "react";
import { C } from "@/lib/tokens";
import { PRO_TREE, APPT_SLOTS } from "@/data/demo";
import { SectionTitle, Badge } from "@/components/ui";
import AiInsight from "@/components/AiInsight";

export default function DataRoom() {
  const [openDom, setOpenDom] = useState<Record<number, boolean>>({});
  const [pro, setPro] = useState<string | null>(null);
  const [slot, setSlot] = useState<number | null>(null);

  const pick = (name: string) => {
    setPro(name);
    setSlot(null);
  };

  return (
    <>
      <SectionTitle
        title="Data room & professionnels"
        sub="Partage volontaire, limité et révocable — vous choisissez chaque document"
      />
      <AiInsight
        sev={C.navy2}
        kind="Suggestion de routage"
        title="Un avocat en droit des affaires est recommandé"
        body="La clause de responsabilité du contrat BÉTA sort de vos usages habituels. Le copilote suggère Me Sophie Lambert (droit des sociétés, dispo sous 72h) et a préparé la sélection de documents à partager."
        cta={[
          { t: "Voir le dossier BÉTA" },
          { t: "Prendre rendez-vous", primary: true, onClick: () => pick("Me Sophie Lambert") },
        ]}
      />

      <div className="grid-half mt16">
        <div className="card" style={{ padding: 0 }}>
          <div
            style={{
              padding: "14px 16px",
              fontSize: 14,
              fontWeight: 700,
              color: C.navy,
              borderBottom: `1px solid ${C.line}`,
            }}
          >
            Réseau de professionnels par domaine
          </div>
          {PRO_TREE.map((d, i) => {
            const isOpen = openDom[i];
            return (
              <div key={i}>
                <button className="tree-row" onClick={() => setOpenDom((o) => ({ ...o, [i]: !o[i] }))}>
                  <span className={`tree-caret ${isOpen ? "open" : ""}`}>▶</span>
                  <span style={{ fontSize: 14, fontWeight: 700, color: C.navy, flex: 1 }}>{d.dom}</span>
                  <Badge color={C.navy2}>
                    {d.pros.length} pro{d.pros.length > 1 ? "s" : ""}
                  </Badge>
                </button>
                {isOpen &&
                  d.pros.map((p, k) => (
                    <div className="pro-row" key={k}>
                      <div className="avatar">{p.init}</div>
                      <div style={{ flex: 1 }}>
                        <div style={{ fontSize: 14, color: C.ink, fontWeight: 600 }}>{p.n}</div>
                        <div style={{ fontSize: 12, color: C.slate }}>{p.r}</div>
                      </div>
                      <Badge color={C.green}>{p.dispo}</Badge>
                      <button className="btn btn-primary btn-xs" onClick={() => pick(p.n)}>
                        Rendez-vous
                      </button>
                    </div>
                  ))}
              </div>
            );
          })}
        </div>

        <div className="card">
          <div style={{ fontSize: 14, fontWeight: 700, color: C.navy, marginBottom: 12 }}>Prise de rendez-vous</div>
          {pro ? (
            <>
              <div style={{ fontSize: 13, color: C.slate, marginBottom: 10 }}>
                Professionnel : <b style={{ color: C.ink }}>{pro}</b>
              </div>
              <div style={{ fontSize: 12, fontWeight: 700, color: C.slate, textTransform: "uppercase", marginBottom: 8 }}>
                Créneaux disponibles
              </div>
              <div style={{ display: "grid", gridTemplateColumns: "1fr 1fr", gap: 8 }}>
                {APPT_SLOTS.map((s, k) => (
                  <div key={k} className={`slot ${slot === k ? "sel" : ""}`} onClick={() => setSlot(k)}>
                    {s}
                  </div>
                ))}
              </div>
              <div style={{ marginTop: 12, display: "flex", gap: 8 }}>
                <button
                  className="btn btn-primary btn-sm"
                  disabled={slot === null}
                  onClick={() =>
                    alert(
                      "Rendez-vous confirmé (démo). Une salle de décision et le partage de documents seront préparés par le copilote."
                    )
                  }
                >
                  Confirmer le rendez-vous
                </button>
                <button
                  className="btn btn-ghost btn-sm"
                  onClick={() => {
                    setPro(null);
                    setSlot(null);
                  }}
                >
                  Changer
                </button>
              </div>
              {slot !== null && (
                <div style={{ marginTop: 10, fontSize: 12, color: C.green }}>
                  ✓ Documents partagés : le copilote joindra le dossier BÉTA (partage limité à 7 jours, révocable).
                </div>
              )}
            </>
          ) : (
            <div style={{ padding: 16, background: C.bg, borderRadius: 10, textAlign: "center" }}>
              <div style={{ fontSize: 14, color: C.ink, fontWeight: 600 }}>Sélectionnez un professionnel</div>
              <div style={{ fontSize: 12, color: C.slate, margin: "6px 0" }}>
                Choisissez un domaine à gauche puis « Rendez-vous » pour ouvrir un créneau et une salle de décision.
              </div>
            </div>
          )}
          <div style={{ marginTop: 14, fontSize: 12, color: C.slate }}>
            🔒 Chaque partage est limité dans le temps, révocable, et journalisé (qui a vu quoi, quand). Aucune
            commission sur les honoraires du professionnel.
          </div>
        </div>
      </div>
    </>
  );
}
