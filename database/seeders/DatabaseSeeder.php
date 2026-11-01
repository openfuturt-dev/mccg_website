<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\PageSeo;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@mccg.ma')],
            ['name' => 'Administrateur MCCG', 'password' => Hash::make(env('ADMIN_PASSWORD', 'ChangeMe123!')), 'role' => 'admin']
        );

        foreach ([
            ['Accueil', 'accueil', 'MCCG | Cabinet de conseil comptable et fiscal au Maroc', 'MCCG accompagne les entreprises au Maroc en tenue comptable, fiscalité, gestion sociale, conseil juridique et accompagnement administratif.'],
            ['À propos', 'a-propos', 'À propos de MCCG — Votre partenaire de confiance', 'Découvrez MCCG, cabinet de conseil marocain engagé aux côtés des dirigeants et entreprises.'],
            ['Services', 'services', 'Services comptables, fiscaux et sociaux | MCCG', 'Découvrez les services MCCG : tenue comptable, conseil fiscal, gestion sociale, paie, accompagnement administratif et conseil aux entreprises.'],
            ['Articles', 'articles', 'Éclairages & actualités — MCCG', 'Analyses pratiques sur la fiscalité, la gestion, la comptabilité et la vie des entreprises au Maroc.'],
            ['Contact', 'contact', 'Contacter MCCG — Parlons de vos enjeux', 'Échangez avec nos consultants et obtenez un accompagnement adapté aux enjeux de votre entreprise.'],
            ['Confidentialité', 'confidentialite', 'Politique de confidentialité — MCCG', 'Politique de confidentialité et traitement des données personnelles par MCCG.'],
            ['Conditions', 'conditions', 'Conditions d’utilisation — MCCG', 'Conditions d’utilisation du site internet MCCG.'],
            ["Marrakech","marrakech",'Cabinet comptable et fiscal à Marrakech | MCCG',"MCCG accompagne les entreprises et entrepreneurs à Marrakech en comptabilité, fiscalité, gestion sociale, conseil juridique et accompagnement administratif."],
            ["Casablanca","casablanca",'Cabinet comptable et fiscal à Casablanca | MCCG','MCCG accompagne les entreprises et entrepreneurs à Casablanca en comptabilité, fiscalité, gestion sociale, conseil juridique et accompagnement administratif.'],
            ["Dubai","dubai",'Conseil comptable et accompagnement à Dubaï | MCCG','MCCG accompagne les entrepreneurs et entreprises à Dubaï dans leurs besoins comptables, fiscaux, administratifs et leur développement entre le Maroc et les Émirats.']

        ] as [$name, $slug, $title, $description]) {
            PageSeo::updateOrCreate(['slug' => $slug], ['page_name' => $name, 'meta_title' => $title, 'meta_description' => $description, 'keywords' => $this->keywords('pages', $slug)]);
        }

        $services = [
            ['Tenue comptable', 'tenue-comptable', 'Une information comptable fiable pour sécuriser vos obligations et piloter votre activité.', 'De la tenue des comptes au suivi des états de synthèse, MCCG organise votre information financière et vous apporte une lecture claire de vos chiffres.', 'calculator'],
            ['Conseil fiscal', 'conseil-fiscal', 'Anticipez vos obligations et maîtrisez durablement vos risques fiscaux.', 'Nous vous accompagnons dans vos déclarations, vos choix structurants, vos opérations exceptionnelles et vos relations avec l’administration fiscale marocaine.', 'scale'],
            ['Audit interne & revue comptable', 'audit-controle', 'Renforcez la fiabilité de vos processus, de vos comptes et de votre gouvernance.', 'Nos missions d’audit interne et de revue comptable identifient les risques, améliorent vos processus et éclairent le pilotage de votre entreprise.', 'check'],
            ['Gestion sociale & RH', 'paie-social', 'Une gestion sociale précise, confidentielle et conforme à vos obligations.', 'Bulletins de paie, déclarations sociales, contrats et conseil RH : nous simplifions votre quotidien tout en sécurisant vos obligations.', 'users'],
            ['Conseil juridique et administratif', 'creation-entreprise', 'Sécurisez vos décisions et donnez à vos projets le cadre juridique et administratif adapté.', 'Création d’entreprise, formalités et accompagnement administratif : nous vous conseillons dans la limite des prestations autorisées et mobilisons, lorsque nécessaire, des professionnels habilités.', 'building'],
            ['Accompagnement des entreprises', 'conseil-gestion', 'Des indicateurs clairs et un conseil stratégique pour décider avec confiance.', 'Budgets, tableaux de bord, analyse de rentabilité et optimisation de trésorerie : nos consultants éclairent vos décisions stratégiques.', 'chart'],
        ];
        foreach ($services as [$title, $slug, $short, $content, $icon]) {
            Service::updateOrCreate(['slug' => $slug], compact('title', 'content', 'icon') + [
                'short_description' => $short, 'meta_title' => $title.' — MCCG', 'meta_description' => $short, 'keywords' => $this->keywords('services', $slug), 'is_active' => true,
            ]);
        }

        $category = Category::updateOrCreate(['slug' => 'conseils'], ['name' => 'Conseils', 'description' => 'Conseils pratiques pour les dirigeants.']);
        Article::updateOrCreate(['slug' => 'bien-preparer-cloture-comptable'], [
            'title' => 'Bien préparer sa clôture comptable : les points essentiels',
            'excerpt' => 'Une méthode claire pour aborder votre clôture avec sérénité et obtenir des comptes réellement utiles.',
            'content' => '<h2>Anticiper pour mieux décider</h2><p>Une clôture réussie commence bien avant la fin de l’exercice. Rapprochements, inventaires et justificatifs doivent être organisés progressivement.</p><h2>Les contrôles indispensables</h2><p>Vérifiez les comptes clients et fournisseurs, la trésorerie, les immobilisations et les charges rattachées au bon exercice. Votre conseiller MCCG peut établir avec vous un calendrier adapté.</p>',
            'category_id' => $category->id, 'meta_title' => 'Préparer sa clôture comptable — MCCG',
            'meta_description' => 'Les contrôles essentiels pour préparer une clôture comptable fiable et utile au pilotage de votre entreprise.',
            'keywords' => 'clôture comptable, entreprise Maroc, conseil', 'status' => 'published', 'published_at' => now()->subDays(5), 'created_by' => $admin->id,
        ]);
    }

    private function keywords(string $group, string $slug): ?string
    {
        $keywords = config("seo_keywords.$group.$slug");

        return $keywords ? implode(', ', $keywords) : null;
    }
}
