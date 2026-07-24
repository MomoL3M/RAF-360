export type BlogPost = {
  slug: string;
  title: string;
  excerpt: string;
  category: string;
  date: string; // ISO
  dateLabel: string;
  readTime: string;
  author: string;
  body: string[]; // paragraphes
};

export const POSTS: BlogPost[] = [
  {
    slug: "anticiper-tresorerie-tpe-pme",
    title: "Anticiper sa trésorerie : le réflexe qui sauve une TPE",
    excerpt:
      "Trop d'entreprises découvrent leur tension de trésorerie trop tard. Voici comment passer d'une gestion subie à un pilotage anticipé, semaine après semaine.",
    category: "Trésorerie",
    date: "2026-06-18",
    dateLabel: "18 juin 2026",
    readTime: "6 min",
    author: "L'équipe RAF 360",
    body: [
      "La trésorerie est le nerf de la guerre des TPE et PME. Pourtant, la plupart des dirigeants la consultent à travers le solde bancaire du jour — une photo, jamais un film. Le résultat : un point bas découvert la veille, quand il ne reste plus qu'à subir.",
      "Anticiper, c'est projeter. En croisant les encaissements attendus, l'échéancier fournisseurs et le calendrier fiscal, on obtient une courbe prévisionnelle qui révèle le creux plusieurs semaines à l'avance. C'est précisément là que se jouent les marges de manœuvre : négocier un délai, activer une ligne court terme, ou décaler un décaissement non critique.",
      "Le copilote RAF 360 automatise ce croisement et signale le risque avant qu'il ne devienne un problème. Il ne se contente pas d'alerter : il compare des scénarios chiffrés, avantages et inconvénients à l'appui, et recommande une action — que vous restez libre de valider.",
      "La règle d'or reste la transparence : toute projection est présentée comme une estimation à valider, jamais comme un fait comptable. C'est la condition d'une confiance durable dans l'outil.",
    ],
  },
  {
    slug: "facturation-electronique-2026-2027",
    title: "Facturation électronique : ce que change le calendrier 2026-2027",
    excerpt:
      "Réception obligatoire, émission progressive, plateformes agréées… Décryptage clair de la réforme et de la façon de s'y préparer sans précipitation.",
    category: "Fiscalité",
    date: "2026-05-30",
    dateLabel: "30 mai 2026",
    readTime: "7 min",
    author: "L'équipe RAF 360",
    body: [
      "La réforme de la facturation électronique concerne toutes les entreprises assujetties à la TVA en France. Deux dates structurent le calendrier : la réception obligatoire au 1er septembre 2026, puis l'obligation d'émission, étalée jusqu'au 1er septembre 2027 pour les TPE et PME.",
      "Concrètement, les factures devront transiter par une plateforme agréée (PDP) plutôt que par un simple PDF envoyé par email. Cela suppose de préparer ses données, ses formats et ses circuits de validation en amont.",
      "La bonne nouvelle : anticiper coûte peu et évite la précipitation. RAF 360 mesure votre niveau de préparation, se connecte à une plateforme partenaire et vous accompagne pas à pas jusqu'à la conformité.",
      "L'objectif n'est pas seulement de cocher une case réglementaire, mais de fluidifier vos échanges et d'améliorer votre suivi des paiements — donc votre trésorerie.",
    ],
  },
  {
    slug: "separation-outil-conseil-reglemente",
    title: "Pourquoi un bon outil ne remplace jamais votre expert",
    excerpt:
      "La frontière entre l'outil qui prépare et le professionnel qui engage sa responsabilité est un principe fondateur. Explications.",
    category: "Conformité",
    date: "2026-05-12",
    dateLabel: "12 mai 2026",
    readTime: "5 min",
    author: "L'équipe RAF 360",
    body: [
      "Un logiciel peut collecter, contrôler, éclairer et préparer. Il ne peut — et ne doit — jamais se substituer au jugement d'un professionnel habilité pour les actes réglementés : conseil juridique, tenue de comptabilité pour autrui, commissariat aux comptes.",
      "Cette séparation stricte protège d'abord le dirigeant. Elle garantit que les décisions engageantes reposent sur la responsabilité d'un expert, et non sur une automatisation opaque.",
      "Chez RAF 360, ce principe se traduit dans le produit : dès qu'un sujet devient réglementé, le copilote oriente vers le professionnel adapté, prépare la sélection de documents, et laisse la décision à l'humain. Aucune commission n'est prélevée sur les honoraires.",
      "C'est cette rigueur qui fait la différence entre un gadget et un véritable système de pilotage digne de confiance.",
    ],
  },
  {
    slug: "checklist-cloture-annuelle",
    title: "Clôture annuelle : la checklist pour aborder l'exercice sereinement",
    excerpt:
      "Charges constatées d'avance, rapprochements, pièces manquantes… La préparation qui transforme la clôture en formalité.",
    category: "Comptabilité",
    date: "2026-04-24",
    dateLabel: "24 avril 2026",
    readTime: "8 min",
    author: "L'équipe RAF 360",
    body: [
      "La clôture annuelle cristallise souvent le stress de l'année : documents éparpillés, écritures oubliées, allers-retours avec le cabinet. Elle peut pourtant devenir une simple formalité, à condition de préparer en continu plutôt qu'en urgence.",
      "La clé est la collecte permanente : chaque pièce déposée, contrôlée et classée au fil de l'eau évite l'accumulation de fin d'exercice. Un score de confiance par document permet d'identifier immédiatement les pièces à fiabiliser.",
      "En pré-clôture, le copilote identifie les charges constatées d'avance, les rapprochements à valider et les pièces manquantes, puis prépare un dossier propre à transmettre à votre expert-comptable.",
      "Résultat : moins d'allers-retours, des honoraires mieux employés, et une clôture qui ne mobilise plus vos nuits.",
    ],
  },
];

export const getPost = (slug: string) => POSTS.find((p) => p.slug === slug);
