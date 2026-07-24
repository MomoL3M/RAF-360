import { useState } from "react";
import { C } from "../lib/tokens.js";
import Logo from "../components/Logo.jsx";

const TOTAL = 3;
const TVA_OPTS = ["Franchise en base", "Réel simplifié", "Réel normal", "Je ne sais pas"];

export default function Onboarding({ onFinish }) {
  const [step, setStep] = useState(0);
  const [siren, setSiren] = useState("");
  const pct = Math.round((step / TOTAL) * 100);

  return (
    <div className="ob-wrap">
      <div style={{ padding: "20px 32px", background: "#fff", borderBottom: `1px solid ${C.line}` }}><Logo size={38} /></div>
      <div style={{ flex: 1, display: "flex", flexDirection: "column", justifyContent: "center", padding: 32 }}>
        <div style={{ textAlign: "center", marginBottom: 30 }}>
          <h1 className="serif" style={{ color: C.navy, fontSize: 30, margin: 0 }}>Bienvenue sur DAF 360</h1>
          <p style={{ color: C.slate, fontSize: 15 }}>Deux informations suffisent pour configurer votre espace.</p>
        </div>
        <div style={{ maxWidth: 560, margin: "0 auto", width: "100%" }}>
          <div className="ob-bar"><div style={{ width: pct + "%" }} /></div>

          {step === 0 && (
            <div className="card">
              <div style={{ fontSize: 12, color: C.gold, fontWeight: 700, letterSpacing: 1 }}>ÉTAPE 1 / {TOTAL}</div>
              <h2 className="serif" style={{ color: C.navy, fontSize: 22, margin: "8px 0 6px" }}>Quel est votre numéro de SIREN ?</h2>
              <p style={{ color: C.slate, fontSize: 13, marginBottom: 18 }}>
                Nous récupérons automatiquement votre forme juridique et vos effectifs depuis les données publiques (SIRENE / INSEE). Vous pouvez saisir n'importe quel SIREN pour tester.
              </p>
              <input className="field" style={{ width: "100%", fontSize: 16, letterSpacing: 2 }} maxLength={11}
                placeholder="123 456 789" value={siren} onChange={(e) => setSiren(e.target.value)} />
              <div style={{ marginTop: 16, display: "flex", gap: 8 }}>
                <button className="btn btn-primary" onClick={() => { if (!siren.replace(/\s/g, "")) setSiren("784 671 695"); setStep(1); }}>Vérifier &amp; continuer</button>
                <button className="btn btn-ghost" onClick={() => { setSiren("784 671 695"); setStep(1); }}>Simuler un SIREN de démo</button>
              </div>
            </div>
          )}

          {step === 1 && (
            <div className="card">
              <div style={{ fontSize: 12, color: C.gold, fontWeight: 700, letterSpacing: 1 }}>ÉTAPE 2 / {TOTAL}</div>
              <h2 className="serif" style={{ color: C.navy, fontSize: 22, margin: "8px 0 6px" }}>Entreprise identifiée</h2>
              <div style={{ background: C.bg, borderRadius: 10, padding: "14px 16px", margin: "12px 0" }}>
                <div className="kv"><span style={{ color: C.slate }}>SIREN</span><b>{siren || "784 671 695"}</b></div>
                <div className="kv"><span style={{ color: C.slate }}>Dénomination</span><b>ARCAN Démo SAS</b></div>
                <div className="kv"><span style={{ color: C.slate }}>Forme juridique</span><b>SAS</b></div>
                <div className="kv"><span style={{ color: C.slate }}>Effectif</span><b>6 à 9 salariés</b></div>
              </div>
              <p style={{ color: C.slate, fontSize: 12, marginBottom: 14 }}>Récupéré depuis SIRENE / INSEE (données de démonstration).</p>
              <button className="btn btn-primary" onClick={() => setStep(2)}>C'est correct, continuer</button>
            </div>
          )}

          {step === 2 && (
            <div className="card">
              <div style={{ fontSize: 12, color: C.gold, fontWeight: 700, letterSpacing: 1 }}>ÉTAPE 3 / {TOTAL}</div>
              <h2 className="serif" style={{ color: C.navy, fontSize: 22, margin: "8px 0 20px" }}>Quel est votre régime de TVA ?</h2>
              <div style={{ display: "grid", gap: 10 }}>
                {TVA_OPTS.map((o) => (
                  <div key={o} className="ob-opt" onClick={() => setStep(3)}>{o}</div>
                ))}
              </div>
            </div>
          )}

          {step >= 3 && (
            <div className="card" style={{ textAlign: "center" }}>
              <div style={{ fontSize: 44, color: C.green }}>✓</div>
              <h2 className="serif" style={{ color: C.navy, fontSize: 22, margin: "8px 0" }}>Votre dossier est prêt</h2>
              <p style={{ color: C.slate, fontSize: 14, marginBottom: 20 }}>
                Le copilote a généré votre matrice d'obligations, votre premier tableau de bord et lancé la veille juridique, fiscale, sectorielle et comptable.
              </p>
              <button className="btn btn-accent" onClick={onFinish}>Accéder au tableau de bord</button>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
