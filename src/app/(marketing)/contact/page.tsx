import { redirect } from "next/navigation";

// La page Contact a été remplacée par le parcours d'onboarding en 2 étapes
// (SIREN → récupération SIRENE/INSEE, puis régime de TVA).
export default function ContactPage() {
  redirect("/app/onboarding");
}
