import type { ReactNode } from "react";

// Icônes de navigation descriptives (currentColor)
const PATHS: Record<string, ReactNode> = {
  dash: (
    <>
      <path d="M4 13a8 8 0 0 1 16 0" />
      <path d="M12 13l3.5-3.5" />
      <circle cx="12" cy="13" r="1.2" fill="currentColor" stroke="none" />
    </>
  ),
  actions: (
    <>
      <rect x="5" y="4" width="14" height="17" rx="2" />
      <path d="M9 3.5h6v2.5H9z" />
      <path d="M8.5 12l2 2 3.5-3.5" />
    </>
  ),
  calendar: (
    <>
      <rect x="4" y="5" width="16" height="15" rx="2" />
      <path d="M4 9h16M8 3v4M16 3v4" />
      <path d="M8.5 13h2M13.5 13h2M8.5 16.5h2" />
    </>
  ),
  cash: (
    <>
      <path d="M4 19h16" />
      <path d="M4 16l4-4 3 2 5-6" />
      <circle cx="16" cy="8" r="1" fill="currentColor" stroke="none" />
      <path d="M17.5 20v-1M6.5 20v-2" />
    </>
  ),
  docs: <path d="M4 7a2 2 0 0 1 2-2h4l2 2h6a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2z" />,
  factures: (
    <>
      <path d="M7 3h7l4 4v13l-2-1.2-2 1.2-2-1.2-2 1.2-2-1.2L7 20z" />
      <path d="M13 3v5h5" />
      <path d="M9.5 12h5M9.5 15h3" />
    </>
  ),
  dataroom: (
    <>
      <path d="M12 3l7 3v5c0 4.5-3 7.6-7 9-4-1.4-7-4.5-7-9V6z" />
      <path d="M9.2 11.5l2 2 3.6-3.6" />
    </>
  ),
  assistant: (
    <>
      <path d="M12 3l1.6 4.6L18 9l-4.4 1.4L12 15l-1.6-4.6L6 9l4.4-1.4z" />
      <path d="M18 15l.7 2 2 .7-2 .7-.7 2-.7-2-2-.7 2-.7z" />
    </>
  ),
};

export function NavIcon({ id }: { id: string }) {
  return (
    <svg
      width="20"
      height="20"
      viewBox="0 0 24 24"
      fill="none"
      stroke="currentColor"
      strokeWidth="1.7"
      strokeLinecap="round"
      strokeLinejoin="round"
    >
      {PATHS[id] || null}
    </svg>
  );
}

export function Sparkle({ color = "#1E2761", size = 13 }: { color?: string; size?: number }) {
  return (
    <svg width={size} height={size} viewBox="0 0 24 24" fill={color}>
      <path d="M12 2l1.9 5.6L19.5 9.5 13.9 11.4 12 17 10.1 11.4 4.5 9.5 10.1 7.6z" />
    </svg>
  );
}
