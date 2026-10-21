# MCCG — Checklist de lancement

Date de validation : ____________________

Responsable : ____________________

## Environnement

- [ ] `APP_ENV=production`.
- [ ] `APP_DEBUG=false`.
- [ ] `APP_URL=https://mc-cg.com`.
- [ ] `APP_KEY` de production présent, sauvegardé et non régénéré pendant un déploiement courant.
- [ ] Identifiants de base de données de production renseignés et testés.
- [ ] `.env` absent de Git et sauvegardé de manière sécurisée hors de la racine web.

## E-mails et file d’attente

- [ ] SMTP de production configuré et authentification testée.
- [ ] `MAIL_FROM_ADDRESS` et `MAIL_FROM_NAME` corrects.
- [ ] `CONTACT_NOTIFICATION_EMAIL` renseigné.
- [ ] `QUEUE_CONNECTION=database`.
- [ ] Migrations exécutées, y compris la table `failed_jobs`.
- [ ] Tâche cron de file d’attente active et vérifiée dans cPanel.
- [ ] `php artisan queue:failed` ne signale aucun échec non traité.
- [ ] E-mail administrateur du formulaire testé.
- [ ] Accusé de réception visiteur testé.

## Fichiers et application

- [ ] `public/build/manifest.json` et les assets compilés sont présents.
- [ ] `php artisan storage:link` exécuté et `public/storage` fonctionnel.
- [ ] Droits d’écriture corrects sur `storage` et `bootstrap/cache`.
- [ ] `php artisan migrate --force` exécuté.
- [ ] `php artisan optimize` exécuté après la configuration finale.
- [ ] Favicon MCCG visible dans le navigateur.

## Vérifications publiques

- [ ] Page d’accueil accessible en HTTPS.
- [ ] Pages À propos, Services, Articles, Contact, Confidentialité et Conditions accessibles.
- [ ] `/sitemap.xml` accessible et valide.
- [ ] `/robots.txt` accessible.
- [ ] Formulaire de contact soumis avec succès sur la production.
- [ ] Limitation à cinq soumissions par minute vérifiée.
- [ ] Téléphone, e-mail, adresse et réseaux sociaux vérifiés.
- [ ] Carte Marrakech vérifiée.
- [ ] Carte Dubai vérifiée.
- [x] Carte Casablanca configurée (33.5846519, -7.6528649).
- [ ] Adresse postale complète du bureau de Casablanca à renseigner.
- [ ] Affichage contrôlé sur téléphone, tablette et ordinateur.

## Administration et contenu

- [ ] Connexion `/admin` testée avec le compte administrateur de production.
- [ ] Mot de passe administrateur temporaire remplacé.
- [ ] Création/modification d’un article testée.
- [ ] Services actifs et contenus publiés relus.
- [ ] Métadonnées SEO principales vérifiées.
- [ ] Formulations juridiques et note relative aux prestations réglementées relues.

## Analytics et référencement

- [ ] Balise Google Ads `AW-987825813` détectée sur les pages publiques.
- [ ] Bannière de consentement testée avec acceptation et refus.
- [ ] Conversion de demande de contact vérifiée dans Google Ads avec une soumission de test réussie (voir `GOOGLE_ADS_TRACKING.md`).
- [ ] Si Plausible est utilisé : domaine suivi correct et script détecté.
- [ ] Propriété Google Search Console validée.
- [ ] `https://mc-cg.com/sitemap.xml` soumis dans Google Search Console.

## Sauvegardes et reprise

- [ ] Sauvegarde initiale de la base de données créée.
- [ ] Sauvegarde de `storage/app/public` créée.
- [ ] Sauvegarde chiffrée du `.env` stockée hors Git.
- [ ] Sauvegardes automatiques quotidiennes/hebdomadaires configurées.
- [ ] Procédure de restauration documentée et testée.

## Validation finale

- [ ] Suite de tests complète réussie.
- [ ] Aucun travail échoué en attente.
- [ ] Aucun lien critique cassé.
- [ ] Validation finale métier obtenue.
- [ ] Lancement approuvé.
