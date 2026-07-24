export default function Logo({ size = 36 }) {
  return (
    <div className="logo">
      <svg width={size} height={size} viewBox="0 0 120 120" fill="none" aria-label="DAF 360">
        <defs>
          <linearGradient id="lgD" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0" stopColor="#12224E" />
            <stop offset="1" stopColor="#2C6FB0" />
          </linearGradient>
          <linearGradient id="lgL" x1="1" y1="1" x2="0" y2="0">
            <stop offset="0" stopColor="#5B94D6" />
            <stop offset="1" stopColor="#A9CBF2" />
          </linearGradient>
        </defs>
        <path d="M28 74 A34 34 0 0 1 92 72" stroke="url(#lgD)" strokeWidth="10" fill="none" strokeLinecap="round" />
        <polygon points="86,60 101,73 84,84" fill="#1A3E7A" />
        <path d="M92 66 A34 34 0 0 1 28 68" stroke="url(#lgL)" strokeWidth="10" fill="none" strokeLinecap="round" />
        <polygon points="34,56 19,67 36,80" fill="#8FBBEE" />
      </svg>
      <div className="txt">
        <div className="daf" style={{ fontSize: size * 0.5 }}>DAF</div>
        <div className="num" style={{ fontSize: size * 0.26 }}>360°</div>
      </div>
    </div>
  );
}
