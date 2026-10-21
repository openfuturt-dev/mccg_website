# Déploiement MCCG sur cPanel

## Prérequis

- PHP 8.3 ou supérieur avec `intl`, `mbstring`, `pdo_mysql`, `fileinfo`, `openssl`, `tokenizer`, `xml` et `zip`.
- MySQL 8 ou une version récente de MariaDB.
- Composer 2.
- Le domaine doit pointer vers le dossier Laravel `public`.
- Une tâche cron ou un gestionnaire de processus doit être disponible pour la file d’attente.

## Arborescence recommandée

Pour conserver le code Laravel hors de la racine web, utiliser cette structure :

```text
/home/USERNAME/
├── mccg_backend/              # Dépôt Git Laravel complet
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── public/                # Source des fichiers publics
│   ├── storage/
│   └── vendor/
└── public_html/
    └── mccg/                  # Racine web configurée dans cPanel
        ├── build/             # Assets Vite suivis dans Git
        ├── images/
        ├── index.php
        └── .htaccess
```

Le `git pull` s’exécute dans `/home/USERNAME/mccg_backend`. Le dossier `public/build` est volontairement suivi dans Git afin que les assets compilés soient disponibles même lorsque Node.js n’est pas installé sur cPanel.

Configurer le document root du domaine `mc-cg.com` sur `/home/USERNAME/public_html/mccg`. Dans ce cas, `APP_URL` reste `https://mc-cg.com` sans suffixe `/mccg`.

## Première mise en ligne

1. Créer la base et l’utilisateur MySQL depuis cPanel, puis leur attribuer les privilèges requis.
2. Copier `.env.example` vers `.env` et configurer `APP_URL`, `DB_*`, SMTP, `CONTACT_NOTIFICATION_EMAIL` et les identifiants administrateur.
3. Utiliser `APP_URL=https://mc-cg.com`, `APP_ENV=production` et `APP_DEBUG=false`.
4. Cloner le dépôt dans `/home/USERNAME/mccg_backend`.
5. Exécuter depuis le terminal cPanel :

```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate --force
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
php artisan optimize
```

6. Copier le contenu du dossier public vers la racine web :

```bash
cp -a /home/USERNAME/mccg_backend/public/. /home/USERNAME/public_html/mccg/
```

7. Dans `/home/USERNAME/public_html/mccg/index.php`, remplacer les trois chemins relatifs par les chemins absolus du backend :

```php
if (file_exists($maintenance = '/home/USERNAME/mccg_backend/storage/framework/maintenance.php')) {
    require $maintenance;
}

require '/home/USERNAME/mccg_backend/vendor/autoload.php';

$app = require_once '/home/USERNAME/mccg_backend/bootstrap/app.php';
```

Ne jamais copier `.env`, `vendor`, `app`, `config`, `database` ou `storage` dans `public_html/mccg`.

8. Créer le lien public des fichiers téléversés vers le backend :

```bash
ln -s /home/USERNAME/mccg_backend/storage/app/public /home/USERNAME/public_html/mccg/storage
```

Générer `APP_KEY` et exécuter `db:seed` uniquement lors de la première installation. Sur les déploiements suivants, conserver la clé existante et appliquer seulement les nouvelles migrations.

Si Node.js n’est pas disponible sur cPanel, utiliser directement le dossier `public/build` suivi dans Git. Avant chaque commit de mise en production qui modifie CSS ou JavaScript, exécuter localement `npm ci && npm run build` et inclure les nouveaux fichiers compilés dans le commit.

## Déploiements suivants

```bash
cd /home/USERNAME/mccg_backend
git pull
composer install --no-dev --optimize-autoloader
php artisan optimize:clear
php artisan migrate --force
php artisan queue:restart
php artisan optimize
rsync -a --delete --exclude=index.php --exclude=storage public/ /home/USERNAME/public_html/mccg/
```

La synchronisation exclut volontairement le `index.php` public personnalisé et le lien `storage`. Vérifier après le déploiement que `public_html/mccg/storage` pointe toujours vers `mccg_backend/storage/app/public`.

Vérifier que `public_html/mccg/build/manifest.json` correspond au code déployé.

## E-mails et file d’attente

Les deux e-mails du formulaire de contact sont placés dans la file Laravel. La configuration de production doit contenir :

```env
QUEUE_CONNECTION=database
CONTACT_NOTIFICATION_EMAIL=adresse-de-reception@mccg.example
```

La migration Laravel `0001_01_01_000002_create_jobs_table.php` crée déjà les tables `jobs`, `job_batches` et `failed_jobs`. Exécuter `php artisan migrate --force` avant de démarrer le traitement de la file.

### Option recommandée : tâche cron cPanel

Dans cPanel > Cron Jobs, créer une tâche exécutée chaque minute. Adapter `USERNAME`, le chemin PHP et le chemin du projet :

```cron
* * * * * php /home/USERNAME/path-to-project/artisan queue:work --stop-when-empty --tries=3 --timeout=90 >> /dev/null 2>&1
```

La commande minimale demandée par certains hébergeurs est :

```bash
php /home/USERNAME/path-to-project/artisan queue:work --stop-when-empty
```

Chaque exécution traite les travaux disponibles puis s’arrête, ce qui convient aux hébergements sans processus permanent.

### Option alternative : worker permanent

Si cPanel propose Supervisor ou un gestionnaire de processus :

```bash
php artisan queue:work database --tries=3 --timeout=90
```

Après chaque déploiement, demander aux workers permanents de recharger le nouveau code :

```bash
php artisan queue:restart
```

### Travaux échoués

Lister les échecs :

```bash
php artisan queue:failed
```

Relancer tous les travaux échoués après avoir corrigé la cause :

```bash
php artisan queue:retry all
```

Supprimer uniquement les anciens échecs déjà traités :

```bash
php artisan queue:flush
```

Surveiller régulièrement les logs dans `storage/logs` et tester les deux e-mails du formulaire après la mise en ligne.

## Sauvegardes de production

Une sauvegarde exploitable doit contenir les éléments suivants :

1. **Base de données** : exporter la base MySQL depuis cPanel/phpMyAdmin ou avec `mysqldump`.
2. **Fichiers téléversés** : sauvegarder intégralement `storage/app/public`.
3. **Lien public** : `public/storage` est un lien symbolique créé par `php artisan storage:link`; sauvegarder sa cible `storage/app/public`, pas seulement le lien.
4. **Configuration** : conserver une copie chiffrée du fichier `.env` dans un emplacement sécurisé hors du dépôt Git et hors de la racine web.
5. **Code/version** : identifier le commit ou l’archive correspondant à chaque sauvegarde.

Fréquence recommandée :

- Base de données : quotidienne.
- Fichiers téléversés : quotidienne, ou immédiatement après une publication importante.
- Sauvegarde complète : hebdomadaire et avant chaque déploiement.
- Rétention : plusieurs versions quotidiennes et hebdomadaires selon l’espace disponible.

Tester périodiquement une restauration sur un environnement séparé. Une sauvegarde non testée ne garantit pas une reprise fiable.

## Sécurité et exploitation

- Ne jamais versionner `.env` ni une sauvegarde contenant des secrets.
- Conserver `APP_DEBUG=false` en production.
- Utiliser un mot de passe administrateur unique et robuste.
- Donner les droits d’écriture au serveur web uniquement sur `storage` et `bootstrap/cache`.
- Exécuter `php artisan optimize:clear && php artisan optimize` après une modification de configuration.
- Vérifier `/sitemap.xml`, `/robots.txt`, `/admin` et le formulaire de contact après chaque mise en ligne.
- Les cartes Marrakech, Casablanca et Dubaï ont des valeurs par défaut dans `config/mccg.php`. Si le `.env` de production contient `MCCG_CASABLANCA_MAPS_URL=""` ou `MCCG_CASABLANCA_MAPS_EMBED_URL=""`, ces valeurs vides masquent la carte : les supprimer ou les remplir comme dans `.env.example`, puis exécuter `php artisan optimize:clear && php artisan optimize`.

Le sitemap est généré dynamiquement : tout article publié ou service actif apparaît automatiquement, sans tâche cron dédiée.

## Origine canonique, redirections et indexation

L’unique origine publique est `https://mc-cg.com`. Le fichier `public/.htaccess` applique avant Laravel les redirections permanentes suivantes en conservant le chemin et la query string :

- HTTP vers HTTPS ;
- `www.mc-cg.com` vers `mc-cg.com` ;
- `/index.php/chemin` vers `/chemin`.

Les règles tiennent compte de `X-Forwarded-Proto` afin d’éviter une boucle lorsque Nginx termine HTTPS devant Apache sur cPanel. Après chaque déploiement, vérifier que `.htaccess` a bien été synchronisé dans la racine web, puis exécuter :

```bash
curl -sS -o /dev/null -D - 'http://mc-cg.com/?source=test'
curl -sS -o /dev/null -D - 'https://www.mc-cg.com/articles/test?source=test'
curl -sS -o /dev/null -D - 'http://www.mc-cg.com/articles/test?source=test'
curl -sS -o /dev/null -D - 'https://mc-cg.com/index.php/articles/test?source=test'
```

Chaque réponse doit être un `301` avec une en-tête `Location` sur `https://mc-cg.com`, le même chemin utile, et la même query string. Si l’hébergeur configure Nginx sans passage par Apache, `.htaccess` sera ignoré : demander alors au support cPanel de reproduire ces trois règles dans le virtual host Nginx, sans ajouter une seconde redirection contradictoire dans Laravel.

Le dépôt ne contient ni CAPTCHA, ni challenge anti-bot, ni configuration Cloudflare/WAF. Si une protection externe est activée, vérifier dans l’interface de l’hébergeur ou du CDN que les requêtes `GET` et `HEAD` vers `/`, `/robots.txt`, `/sitemap.xml`, `/articles/*` et `/services/*` ne reçoivent pas de challenge. Ne pas désactiver globalement les protections de `/admin` ou du formulaire de contact. Préférer, lorsqu’elle existe, la reconnaissance de robots vérifiés du fournisseur à une règle fondée uniquement sur le texte du User-Agent.
