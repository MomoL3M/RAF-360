export const TODAY = "2026-02-10"; // date de référence de la démo

const MONTHS = [
  "janv.",
  "févr.",
  "mars",
  "avr.",
  "mai",
  "juin",
  "juil.",
  "août",
  "sept.",
  "oct.",
  "nov.",
  "déc.",
];

export function frDate(iso: string): string {
  const [, m, d] = iso.split("-");
  return `${+d} ${MONTHS[+m - 1]}`;
}

export const isOverdue = (iso: string): boolean => iso < TODAY;
