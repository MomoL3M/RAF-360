import { useState, useRef, useEffect } from "react";
import { C } from "../lib/tokens.js";
import { INITIAL_CHAT } from "../data/demo.js";
import { SectionTitle } from "../components/ui.jsx";
import AiInsight from "../components/AiInsight.jsx";

export default function Assistant({ onNav }) {
  const [chat, setChat] = useState(INITIAL_CHAT);
  const [input, setInput] = useState("");
  const boxRef = useRef(null);

  useEffect(() => {
    if (boxRef.current) boxRef.current.scrollTop = boxRef.current.scrollHeight;
  }, [chat]);

  const send = () => {
    const v = input.trim();
    if (!v) return;
    setChat((c) => [
      ...c,
      { role: "user", t: v },
      { role: "ia", t: "Ceci est une maquette : la réponse réelle serait sourcée, accompagnée d'un score de confiance et, si le sujet est réglementé, orientée vers le professionnel adapté avec proposition de scénarios." },
    ]);
    setInput("");
  };

  return (
    <>
      <SectionTitle title="Assistant IA" sub="Réponses sourcées, à périmètre restreint — jamais un conseil réglementé autonome" />
      <div style={{ marginBottom: 14 }}>
        <AiInsight
          sev={C.blue}
          kind="Le copilote a repéré 3 choses aujourd'hui"
          body="1) Trésorerie sous tension en février (−55 k€). 2) TVA de décembre en retard (3 420 €). 3) Contrat BÉTA à faire relire par un avocat. Cliquez pour agir."
          cta={[
            { t: "Trésorerie", onClick: () => onNav("cash") },
            { t: "Calendrier", onClick: () => onNav("calendar") },
            { t: "Data room", onClick: () => onNav("dataroom") },
          ]}
        />
      </div>
      <div className="card" style={{ padding: 0, overflow: "hidden" }}>
        <div style={{ background: C.ice + "44", padding: "8px 16px", fontSize: 12, color: C.navy }}>
          Vous interagissez avec une IA. Les analyses sont préparatoires et à valider ; les sujets réglementés sont orientés vers un professionnel.
        </div>
        <div ref={boxRef} style={{ padding: 20, maxHeight: 340, overflowY: "auto" }}>
          {chat.map((m, i) => (
            <div key={i} style={{ display: "flex", justifyContent: m.role === "user" ? "flex-end" : "flex-start", marginBottom: 14 }}>
              <div style={{ maxWidth: "75%", background: m.role === "user" ? C.navy : C.bg, color: m.role === "user" ? "#fff" : C.ink, padding: "10px 14px", borderRadius: 12, fontSize: 14 }}>
                {m.t}
                {m.src && (
                  <div style={{ fontSize: 11, color: m.role === "user" ? C.ice : C.slate, marginTop: 6, borderTop: `1px solid ${m.role === "user" ? "#ffffff33" : C.line}`, paddingTop: 6 }}>{m.src}</div>
                )}
              </div>
            </div>
          ))}
        </div>
        <div style={{ display: "flex", gap: 8, padding: 16, borderTop: `1px solid ${C.line}` }}>
          <input className="field" style={{ flex: 1 }} value={input} placeholder="Posez une question sur votre dossier…"
            onChange={(e) => setInput(e.target.value)} onKeyDown={(e) => { if (e.key === "Enter") send(); }} />
          <button className="btn btn-primary" onClick={send}>Envoyer</button>
        </div>
      </div>
    </>
  );
}
