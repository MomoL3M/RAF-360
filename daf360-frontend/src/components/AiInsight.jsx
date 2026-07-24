import { C } from "../lib/tokens.js";
import { Sparkle } from "./Icon.jsx";

// Élément signature du produit : le Copilote DAF 360.
// Alerte, anticipe, propose des scénarios (avantages / inconvénients) + recommandation.
export default function AiInsight({ sev = C.navy, kind = "Anticipation", title, body, scenarios, reco, cta, src, style }) {
  return (
    <div className="ai" style={{ "--sev": sev, ...style }}>
      <div className="ai-tag" style={{ "--sev": sev }}>
        <Sparkle color={sev} /> Copilote DAF 360 · {kind}
      </div>
      {title && <div className="ai-title">{title}</div>}
      {body && <div className="ai-body">{body}</div>}
      {scenarios && <Scenarios list={scenarios} reco={reco} />}
      {cta && (
        <div style={{ marginTop: 12, display: "flex", gap: 8, flexWrap: "wrap" }}>
          {cta.map((c, i) => (
            <button key={i} className={`btn ${c.primary ? "btn-primary" : "btn-ghost"} btn-xs`} onClick={c.onClick}>
              {c.t}
            </button>
          ))}
        </div>
      )}
      {src && <div className="ai-src">{src}</div>}
    </div>
  );
}

function Scenarios({ list, reco }) {
  return (
    <>
      <div className="scn">
        {list.map((s, i) => (
          <div className="scn-col" key={i}>
            <div className="scn-h">{s.h}</div>
            {(s.pro || []).map((p, k) => (
              <div className="scn-li" key={"p" + k}>
                <span style={{ color: C.green, fontWeight: 800 }}>✓</span><span>{p}</span>
              </div>
            ))}
            {(s.con || []).map((p, k) => (
              <div className="scn-li" key={"c" + k}>
                <span style={{ color: C.red, fontWeight: 800 }}>✗</span><span>{p}</span>
              </div>
            ))}
          </div>
        ))}
      </div>
      {reco && <div className="scn-reco"><b>Recommandation du copilote :</b> {reco}</div>}
    </>
  );
}
