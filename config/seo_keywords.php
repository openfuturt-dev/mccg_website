<?php

/*
 * Default SEO keywords, by topic.
 * They are copied into the database by a migration and the seeder, and can then
 * be edited in the admin (Pages SEO and Services). Pages with no keywords in the
 * database fall back to "default".
 */

$comptabilite = [
    'Cabinet de comptabilité au Maroc', 'Prestataire de services comptables', 'Externalisation comptable',
    'Gestion comptable des entreprises', 'Tenue de comptabilité', 'Suivi des opérations comptables',
    'Organisation comptable d’entreprise', 'Assistance comptable professionnelle', 'Accompagnement comptable des PME',
    'Comptabilité des petites entreprises', 'Conseil en comptabilité', 'Gestion des pièces comptables',
    'Préparation des états financiers', 'Clôture comptable annuelle', 'Révision comptable',
];

$fiscalite = [
    'Conseil fiscal au Maroc', 'Fiscalité des entreprises marocaines', 'Accompagnement fiscal des entreprises',
    'Déclarations fiscales au Maroc', 'Gestion des obligations fiscales', 'Impôt sur les sociétés au Maroc',
    'Déclaration de TVA au Maroc', 'Impôt sur le revenu professionnel', 'Réglementation fiscale marocaine',
    'Optimisation fiscale légale', 'Assistance en matière fiscale', 'Calendrier des obligations fiscales',
    'Conformité fiscale des entreprises',
];

$gestionFinanciere = [
    'Conseil en gestion financière', 'Analyse financière d’entreprise', 'Diagnostic financier', 'Suivi de trésorerie',
    'Gestion de trésorerie d’entreprise', 'Élaboration de tableaux de bord financiers', 'Analyse des coûts de revient',
    'Contrôle de gestion', 'Prévision budgétaire', 'Planification financière', 'Rentabilité des entreprises',
    'Amélioration de la performance financière', 'Gestion des risques financiers', 'Aide à la décision financière',
];

$conseilEntreprise = [
    'Cabinet de conseil aux entreprises au Maroc', 'Conseil en organisation d’entreprise', 'Stratégie de développement d’entreprise',
    'Accompagnement des dirigeants', 'Conseil en management', 'Diagnostic organisationnel', 'Amélioration des processus internes',
    'Accompagnement des TPE et PME', 'Conseil en performance d’entreprise', 'Structuration des entreprises',
    'Plan stratégique d’entreprise', 'Assistance aux entrepreneurs', 'Accompagnement à la croissance',
    'Conseil en développement commercial',
];

$creation = [
    'Formalités de création d’entreprise au Maroc', 'Démarches administratives pour créer une société',
    'Accompagnement à la création de société', 'Constitution de société au Maroc', 'Choix de la forme juridique',
    'Création de SARL au Maroc', 'Création de SARL AU au Maroc', 'Immatriculation d’entreprise',
    'Démarrage d’activité professionnelle', 'Accompagnement des porteurs de projets', 'Business plan au Maroc',
    'Étude de faisabilité d’un projet', 'Conseil aux créateurs d’entreprise', 'Développement des jeunes entreprises',
];

$paie = [
    'Gestion de la paie au Maroc', 'Externalisation de la paie', 'Établissement des bulletins de paie',
    'Déclarations sociales au Maroc', 'Gestion administrative du personnel', 'Conseil en gestion des ressources humaines',
    'Gestion des charges sociales', 'Accompagnement des employeurs', 'Administration du personnel',
    'Obligations sociales des entreprises',
];

$audit = [
    'Audit financier au Maroc', 'Audit organisationnel', 'Contrôle interne des entreprises',
    'Évaluation des procédures comptables', 'Diagnostic de gestion', 'Vérification de la conformité comptable',
    'Gestion des risques d’entreprise', 'Fiabilité de l’information financière',
    'Accompagnement à la conformité réglementaire', 'Amélioration des procédures internes',
];

// Main terms for overview pages (home, services, about, contact).
$principaux = [
    'Cabinet de comptabilité au Maroc', 'Services comptables au Maroc', 'Cabinet de conseil aux entreprises au Maroc',
    'Expert en gestion d’entreprise au Maroc', 'Externalisation comptable', 'Tenue de comptabilité', 'Conseil fiscal au Maroc',
    'Fiscalité des entreprises marocaines', 'Conseil en gestion financière', 'Accompagnement des TPE et PME',
    'Formalités de création d’entreprise au Maroc', 'Gestion de la paie au Maroc', 'Contrôle interne des entreprises',
    'Cabinet de conseil à Casablanca', 'Cabinet comptable à Marrakech', 'Cabinet de conseil à Rabat',
    'Accompagnement des entreprises à Tanger',
];

return [
    'default' => $principaux,

    // Keyed by page_seos.slug
    'pages' => [
        'accueil' => $principaux,
        'services' => $principaux,
        'a-propos' => $principaux,
        'contact' => $principaux,
        'articles' => ['Réglementation fiscale marocaine', 'Calendrier des obligations fiscales', 'Fiscalité des entreprises marocaines', 'Gestion comptable des entreprises', 'Obligations sociales des entreprises', 'Accompagnement des dirigeants'],
        'marrakech' => ['Cabinet comptable à Marrakech', 'Cabinet de comptabilité au Maroc', 'Conseil fiscal au Maroc', 'Gestion de la paie au Maroc', 'Accompagnement des TPE et PME', 'Formalités de création d’entreprise au Maroc', 'Services comptables au Maroc'],
        'casablanca' => ['Cabinet comptable à Casablanca', 'Cabinet de conseil à Casablanca', 'Conseil en gestion à Casablanca', 'Conseil fiscal à Casablanca', 'Services comptables au Maroc', 'Accompagnement des TPE et PME', 'Formalités de création d’entreprise au Maroc'],
        'dubai' => ['Cabinet de conseil aux entreprises au Maroc', 'Stratégie de développement d’entreprise', 'Accompagnement à la croissance', 'Conseil en gestion financière', 'Accompagnement des dirigeants'],
    ],

    // Keyed by services.slug
    'services' => [
        'tenue-comptable' => $comptabilite,
        'conseil-fiscal' => $fiscalite,
        'audit-controle' => $audit,
        'paie-social' => $paie,
        'creation-entreprise' => $creation,
        'conseil-gestion' => [...$gestionFinanciere, ...$conseilEntreprise],
    ],
];
