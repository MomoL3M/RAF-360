import type { CSSProperties, ReactNode } from "react";
import { C } from "@/lib/tokens";
import { Sparkle } from "./Icon";

export type Scenario = { h: string; pro?: string[]; con?: string[] };
export type Cta = { t: string; primary?: boolean; onClick?: () => void };

export type AiInsightProps = {
  sev?: string;
  kind?: string;
  title?: ReactNode;
  body?: ReactNode;
  scenarios?: Scenario[];
  reco?: string;
  cta?: Cta[];
  src?: string;
  style?: CSSProperties;
};

// Élément signature du produit : le Copilote RAF 360.
// Alerte, anticipe, propose des scénarios (avantages / inconvénients) + recommandation.
export default function AiInsight({
  sev = C.navy,
  kind = "Anticipation",
  title,
  body,
  scenarios,
  reco,
  cta,
  src,
  style,
}: AiInsightProps) {
  return (
    <div className="ai" style={{ ["--sev" as string]: sev, ...style } as CSSProperties}>
      <div className="ai-tag" style={{ ["--sev" as string]: sev } as CSSProperties}>
        <Sparkle color={sev} /> Copilote RAF 360 · {kind}
      </div>
      {title && <div className="ai-title">{title}</div>}
      {body && <div className="ai-body">{body}</div>}
      {scenarios && <Scenarios list={scenarios} reco={reco} />}
      {cta && (
        <div style={{ marginTop: 12, display: "flex", gap: 8, flexWrap: "wrap" }}>
          {cta.map((c, i) => (
            <button
              key={i}
              className={`btn ${c.primary ? "btn-primary" : "btn-ghost"} btn-xs`}
              onClick={c.onClick}
            >
              {c.t}
            </button>
          ))}
        </div>
      )}
      {src && <div className="ai-src">{src}</div>}
    </div>
  );
}

function Scenarios({ list, reco }: { list: Scenario[]; reco?: string }) {
  return (
    <>
      <div className="scn">
        {list.map((s, i) => (
          <div className="scn-col" key={i}>
            <div className="scn-h">{s.h}</div>
            {(s.pro || []).map((p, k) => (
              <div className="scn-li" key={"p" + k}>
                <span style={{ color: C.green, fontWeight: 800 }}>✓</span>
                <span>{p}</span>
              </div>
            ))}
            {(s.con || []).map((p, k) => (
              <div className="scn-li" key={"c" + k}>
                <span style={{ color: C.red, fontWeight: 800 }}>✗</span>
                <span>{p}</span>
              </div>
            ))}
          </div>
        ))}
      </div>
      {reco && (
        <div className="scn-reco">
          <b>Recommandation du copilote :</b> {reco}
        </div>
      )}
    </>
  );
}
