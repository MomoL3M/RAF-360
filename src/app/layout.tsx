import type { Metadata } from "next";
import { Fraunces, Inter } from "next/font/google";
import "./globals.css";

const fraunces = Fraunces({
  subsets: ["latin"],
  variable: "--font-serif",
  display: "swap",
  axes: ["opsz"],
});

const inter = Inter({
  subsets: ["latin"],
  variable: "--font-sans",
  display: "swap",
});

const SITE = "https://raf360.fr";

export const metadata: Metadata = {
  metadataBase: new URL(SITE),
  title: {
    default: "RAF 360 — Le copilote financier des TPE et PME",
    template: "%s — RAF 360",
  },
  description:
    "RAF 360 centralise le pilotage financier, comptable, fiscal, social et juridique des TPE et PME : collecte et contrôle des documents, suivi des échéances, trésorerie éclairée, et orientation vers le bon professionnel habilité.",
  keywords: [
    "pilotage financier",
    "TPE PME",
    "DAF externalisé",
    "trésorerie",
    "conformité",
    "comptabilité",
    "fiscalité",
    "copilote IA",
  ],
  openGraph: {
    title: "RAF 360 — Le copilote financier des TPE et PME",
    description:
      "Centralisez votre pilotage financier, comptable, fiscal, social et juridique. Anticipez vos échéances, éclairez votre trésorerie, et engagez le bon professionnel au bon moment.",
    type: "website",
    locale: "fr_FR",
    siteName: "RAF 360",
  },
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="fr" className={`${fraunces.variable} ${inter.variable}`}>
      <body>{children}</body>
    </html>
  );
}
