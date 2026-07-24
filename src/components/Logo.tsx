type LogoProps = {
  size?: number;
  dark?: boolean;
  showWord?: boolean;
  spin?: boolean;
};

/** Marque orbitale RAF 360 : deux rubans entrelacés + flèche/boussole ambre. */
export function LogoMark({ size = 40, spin = false }: { size?: number; spin?: boolean }) {
  return (
    <svg
      width={size}
      height={size}
      viewBox="0 0 120 120"
      fill="none"
      aria-hidden="true"
      style={{ display: "block", flexShrink: 0 }}
    >
      <defs>
        <linearGradient id="rafNavy" x1="0" y1="0" x2="1" y2="1">
          <stop offset="0" stopColor="#0B1E45" />
          <stop offset="1" stopColor="#2C6FB0" />
        </linearGradient>
        <linearGradient id="rafBlue" x1="1" y1="1" x2="0" y2="0">
          <stop offset="0" stopColor="#5B94D6" />
          <stop offset="1" stopColor="#9CC2F0" />
        </linearGradient>
        <linearGradient id="rafGold" x1="0" y1="0" x2="1" y2="1">
          <stop offset="0" stopColor="#F6B743" />
          <stop offset="1" stopColor="#E08A0E" />
        </linearGradient>
      </defs>

      <g
        style={
          spin
            ? { transformOrigin: "60px 60px", animation: "spin-slow 44s linear infinite" }
            : undefined
        }
      >
        {/* ruban marine (haut → droite → bas) */}
        <path
          d="M60 16 A44 44 0 0 1 96 78"
          stroke="url(#rafNavy)"
          strokeWidth="14"
          strokeLinecap="round"
          fill="none"
        />
        {/* ruban bleu clair (bas → gauche → haut) */}
        <path
          d="M60 104 A44 44 0 0 1 24 42"
          stroke="url(#rafBlue)"
          strokeWidth="14"
          strokeLinecap="round"
          fill="none"
        />
        {/* accents entrelacés */}
        <path
          d="M96 78 A44 44 0 0 1 60 104"
          stroke="url(#rafNavy)"
          strokeWidth="14"
          strokeLinecap="round"
          fill="none"
          opacity="0.55"
        />
        <path
          d="M24 42 A44 44 0 0 1 60 16"
          stroke="url(#rafBlue)"
          strokeWidth="14"
          strokeLinecap="round"
          fill="none"
          opacity="0.7"
        />
      </g>

      {/* flèche / boussole ambre au centre, pointant vers le haut-droite */}
      <g transform="translate(60 60) scale(0.085) translate(-256 -256)">
        <path
          d="M444.52 3.52 20.6 195.63c-37.53 17-31.52 72.71 8.72 81.14L155.62 302l25.23 126.3c8.42 40.24 64.13 46.25 81.14 8.72L454.06 43.06c11.16-24.71-14.83-50.7-39.54-39.54z"
          fill="url(#rafGold)"
        />
      </g>
    </svg>
  );
}

export default function Logo({ size = 36, dark = false, showWord = true, spin = false }: LogoProps) {
  return (
    <span style={{ display: "inline-flex", alignItems: "center", gap: 11, textDecoration: "none" }}>
      <LogoMark size={size} spin={spin} />
      {showWord && (
        <span
          className="mkt-serif"
          style={{
            fontWeight: 700,
            fontSize: size * 0.6,
            letterSpacing: "0.5px",
            lineHeight: 1,
            whiteSpace: "nowrap",
          }}
        >
          <span style={{ color: dark ? "#ffffff" : "#0B1E45" }}>RAF</span>{" "}
          <span style={{ color: "#EDA323" }}>360</span>
        </span>
      )}
    </span>
  );
}
