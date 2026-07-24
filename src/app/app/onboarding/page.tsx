"use client";

import { useState } from "react";
import { useRouter } from "next/navigation";
import { C } from "@/lib/tokens";
import Logo from "@/components/Logo";

const TOTAL = 2;
const TVA_OPTS = ["Franchise en base", "Réel simplifié", "Réel normal", "Je ne sais pas"];

export default function Onboarding() {
  const router = useRouter();
  // step 0 = SIREN (avec sous-état "récupéré"), step 1 = TVA, step 2 = terminé
  const [step, setStep] = useState(0);
  const [retrieved, setRetrieved] = useState(false);
  const [siren, setSiren] = useState("");

  const pct = step === 0 ? (retrieved ? 30 : 6) : step === 1 ? 62 : 100;
  // clé pour rejouer l'animation d'entrée de la carte à chaque transition
  const animKey = `${step}-${retrieved}`;

  const fetchSiren = (demo?: boolean) => {
    if (demo || !siren.replace(/\s/g, "")) setSiren("784 671 695");
    setRetrieved(true);
  };

  return (
    <div className="ob-wrap">
      <div style={{ padding: "20px 32px", background: "#fff", borderBottom: `1px solid ${C.line}` }}>
        <Logo size={38} />
      </div>
      <div style={{ flex: 1, display: "flex", flexDirection: "column", justifyContent: "center", padding: 32 }}>
        <div style={{ textAlign: "center", marginBottom: 30 }}>
          <h1 className="serif" style={{ color: C.navy, fontSize: 30, margin: 0 }}>
            Bienvenue sur RAF 360
          </h1>
          <p style={{ color: C.slate, fontSize: 15 }}>Deux informations suffisent pour configurer votre espace.</p>
        </div>
        <div style={{ maxWidth: 560, margin: "0 auto", width: "100%" }}>
          <div className="ob-bar">
            <div style={{ width: pct + "%" }} />
          </div>

          <div key={animKey} className="ob-card-anim">
            {/* ---------- ÉTAPE 1 : SIREN ---------- */}
            {step === 0 && !retrieved && (
              <div className="card">
                <div style={{ fontSize: 12, color: C.gold, fontWeight: 700, letterSpacing: 1 }}>ÉTAPE 1 / {TOTAL}</div>
                <h2 className="serif" style={{ color: C.navy, fontSize: 22, margin: "8px 0 6px" }}>
                  Quel est votre numéro de SIREN ?
                </h2>
                <p style={{ color: C.slate, fontSize: 13, marginBottom: 18 }}>
                  Nous récupérons automatiquement votre forme juridique et vos effectifs depuis les données publiques
                  (SIRENE / INSEE). Vous pouvez saisir n&apos;importe quel SIREN pour tester.
                </p>
                <input
                  className="field"
                  style={{ width: "100%", fontSize: 16, letterSpacing: 2 }}
                  maxLength={11}
                  placeholder="123 456 789"
                  value={siren}
                  onChange={(e) => setSiren(e.target.value)}
                  onKeyDown={(e) => {
                    if (e.key === "Enter") fetchSiren();
                  }}
                />
                <div style={{ marginTop: 16, display: "flex", gap: 8, flexWrap: "wrap" }}>
                  <button className="btn btn-primary" onClick={() => fetchSiren()}>
                    Vérifier &amp; récupérer mes infos
                  </button>
                  <button className="btn btn-ghost" onClick={() => fetchSiren(true)}>
                    Simuler un SIREN de démo
                  </button>
                </div>
              </div>
            )}

            {/* ---------- ÉTAPE 1 (suite) : infos récupérées SIRENE/INSEE ---------- */}
            {step === 0 && retrieved && (
              <div className="card">
                <div style={{ display: "flex", alignItems: "center", justifyContent: "space-between", flexWrap: "wrap", gap: 8 }}>
                  <div style={{ fontSize: 12, color: C.gold, fontWeight: 700, letterSpacing: 1 }}>ÉTAPE 1 / {TOTAL}</div>
                  <span
                    style={{
                      display: "inline-flex",
                      alignItems: "center",
                      gap: 6,
                      fontSize: 12,
                      fontWeight: 600,
                      color: C.green,
                      background: "#e9f6f0",
                      padding: "4px 10px",
                      borderRadius: 20,
                    }}
                  >
                    ✓ Récupéré depuis SIRENE / INSEE
                  </span>
                </div>
                <h2 className="serif" style={{ color: C.navy, fontSize: 22, margin: "8px 0 6px" }}>
                  Entreprise identifiée
                </h2>
                <p style={{ color: C.slate, fontSize: 13, marginBottom: 6 }}>
                  Vérifiez les informations récupérées automatiquement, puis continuez.
                </p>
                <div style={{ background: C.bg, borderRadius: 10, padding: "14px 16px", margin: "12px 0" }}>
                  <div className="kv">
                    <span style={{ color: C.slate }}>SIREN</span>
                    <b>{siren || "784 671 695"}</b>
                  </div>
                  <div className="kv">
                    <span style={{ color: C.slate }}>Dénomination</span>
                    <b>ARCAN Démo SAS</b>
                  </div>
                  <div className="kv">
                    <span style={{ color: C.slate }}>Forme juridique</span>
                    <b>SAS</b>
                  </div>
                  <div className="kv">
                    <span style={{ color: C.slate }}>Effectif</span>
                    <b>6 à 9 salariés</b>
                  </div>
                </div>
                <div style={{ display: "flex", gap: 8, flexWrap: "wrap" }}>
                  <button className="btn btn-primary" onClick={() => setStep(1)}>
                    C&apos;est correct, continuer
                  </button>
                  <button
                    className="btn btn-ghost"
                    onClick={() => {
                      setRetrieved(false);
                      setSiren("");
                    }}
                  >
                    Modifier le SIREN
                  </button>
                </div>
              </div>
            )}

            {/* ---------- ÉTAPE 2 : régime de TVA ---------- */}
            {step === 1 && (
              <div className="card">
                <div style={{ fontSize: 12, color: C.gold, fontWeight: 700, letterSpacing: 1 }}>ÉTAPE 2 / {TOTAL}</div>
                <h2 className="serif" style={{ color: C.navy, fontSize: 22, margin: "8px 0 20px" }}>
                  Quel est votre régime de TVA ?
                </h2>
                <div style={{ display: "grid", gap: 10 }}>
                  {TVA_OPTS.map((o) => (
                    <div key={o} className="ob-opt" onClick={() => setStep(2)}>
                      {o}
                    </div>
                  ))}
                </div>
                <button
                  className="btn btn-ghost btn-sm"
                  style={{ marginTop: 16 }}
                  onClick={() => setStep(0)}
                >
                  ← Revenir à l&apos;étape précédente
                </button>
              </div>
            )}

            {/* ---------- Terminé ---------- */}
            {step >= 2 && (
              <div className="card" style={{ textAlign: "center" }}>
                <div style={{ fontSize: 44, color: C.green }}>✓</div>
                <h2 className="serif" style={{ color: C.navy, fontSize: 22, margin: "8px 0" }}>
                  Votre dossier est prêt
                </h2>
                <p style={{ color: C.slate, fontSize: 14, marginBottom: 20 }}>
                  Le copilote a généré votre matrice d&apos;obligations, votre premier tableau de bord et lancé la veille
                  juridique, fiscale, sectorielle et comptable.
                </p>
                <button className="btn btn-accent" onClick={() => router.push("/app/dashboard")}>
                  Accéder au tableau de bord
                </button>
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  );
}
