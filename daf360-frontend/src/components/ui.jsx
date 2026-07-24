import { C } from "../lib/tokens.js";

export function Badge({ children, color = C.slate }) {
  return <span className="badge" style={{ color, background: color + "1A" }}>{children}</span>;
}

export function Chip({ children, color, borderColor }) {
  return (
    <span className="chip" style={{ color: color || undefined, borderColor: borderColor || undefined }}>
      {children}
    </span>
  );
}

export function SectionTitle({ title, sub }) {
  return (
    <div className="section-head">
      <h2 className="section">{title}</h2>
      {sub && <p className="section-sub">{sub}</p>}
    </div>
  );
}

export function Donut({ pct, size = 90, color = C.gold }) {
  const r = size / 2 - 8;
  const c = 2 * Math.PI * r;
  return (
    <svg width={size} height={size}>
      <circle cx={size / 2} cy={size / 2} r={r} fill="none" stroke={C.line} strokeWidth="8" />
      <circle cx={size / 2} cy={size / 2} r={r} fill="none" stroke={color} strokeWidth="8"
        strokeDasharray={c} strokeDashoffset={c * (1 - pct / 100)} strokeLinecap="round"
        transform={`rotate(-90 ${size / 2} ${size / 2})`} />
      <text x="50%" y="50%" textAnchor="middle" dy="6" fontSize="20" fontWeight="700" fill={C.navy}>{pct}%</text>
    </svg>
  );
}

export function Spark({ pts, w = 220, h = 54, color = C.navy }) {
  const max = Math.max(...pts), min = Math.min(...pts), rng = (max - min) || 1, step = w / (pts.length - 1);
  const p = pts.map((v, i) => `${(i * step).toFixed(1)},${(h - ((v - min) / rng) * (h - 10) - 5).toFixed(1)}`).join(" ");
  return (
    <svg width={w} height={h} style={{ display: "block", maxWidth: "100%" }}>
      <polygon points={`0,${h} ${p} ${w},${h}`} fill={color + "14"} />
      <polyline points={p} fill="none" stroke={color} strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round" />
    </svg>
  );
}
