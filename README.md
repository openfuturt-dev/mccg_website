# MCCG Website

Corporate website and content administration system for **MCCG**, a Moroccan cabinet providing accounting support and business consulting services.

The application provides a French public website, an authenticated Filament administration panel, article and service publishing, page-level SEO management, contact lead tracking, a dynamic sitemap, and a cPanel-compatible deployment workflow.

## 1. Current project status

The application is currently functional and includes:

- A complete responsive public website in French.
- A Filament administration panel available at `/admin`.
- Article, category, service, page SEO, and contact-message management.
- Draft, immediate publication, and scheduled publication logic for articles.
- Dynamic SEO metadata, Open Graph tags, canonical URLs, and JSON-LD.
- A dynamic `sitemap.xml` and `robots.txt`.
- A validated and rate-limited contact form.
- Queued administrator and visitor contact-email notifications with failure logging.
- Administrator-only user management and policy-based role permissions.
- MCCG branding using coral, charcoal, gray, Montserrat, and Inter.
- Lightweight viewport animations with reduced-motion support.
- Automated feature tests for public and administrative routes.
- Production assets compiled by Vite.
- Real MCCG contact details, social profiles, opening hours, and office locations.
- An accessible three-city Google Maps carousel for Marrakech, Casablanca, and Dubai.
- Mobile and tablet layouts optimized from 360px through 1024px.
- Legally cautious service and marketing wording for regulated professional activities in Morocco.
- A multi-resolution MCCG favicon generated from the official coral brand mark.

### 1.1 Application snapshot — 5 July 2026

| Area | Current state |
|---|---|
| Public website | Complete French website with 9 public content pages, service/article detail routes, sitemap, and robots response |
| Administration | Filament panel at `/admin` with users, articles, categories, services, page SEO, and contact messages |
| Content | Six seeded services, one seeded category, one sample published article, database-managed articles and page SEO |
| Contact | Validated and throttled form, persistent lead storage, two queued email notifications, Filament status tracking |
| Business details | Centralized in `config/mccg.php` with environment overrides |
| Office maps | Interactive carousel with city tabs, arrow controls, embedded maps, external Google Maps links, and missing-map fallbacks |
| Responsive design | Phone-first layouts for 360–600px, two-column tablet layouts where appropriate, desktop preserved at 1024px+ |
| Accessibility | Labeled forms, keyboard-aware mobile menu and map tabs, 44px controls, focus states, reduced-motion handling |
| SEO | Database-managed page metadata, canonical and Open Graph tags, valid LocalBusiness/ProfessionalService and Article JSON-LD |
| Legal positioning | Public wording uses accounting-support, fiscal, social, administrative, internal-audit, and business-advisory terminology |
| Automated verification | 21 PHPUnit tests passing with 158 assertions |
| Production domain | `https://mc-cg.com` |

### 1.2 Current MCCG business information

| Detail | Value |
|---|---|
| Telephone | `05 24 43 83 70` |
| Email | `contact@mc-cg.com` |
| Main office | 92, Bd Zerktouni, Appt 6, 2ème étage, Guéliz, Marrakech |
| Other offices | Casablanca and Dubai |
| Opening hours | Monday–Friday, 09:00–18:00 |
| LinkedIn | `https://www.linkedin.com/in/majdchraibi` |
| Instagram | `https://www.instagram.com/mccg.consulting` |

The Casablanca map uses the office coordinates `33.5846519, -7.6528649`. The street address shown is a placeholder (“Bureau MCCG Casablanca”) until the full address is confirmed.

## 2. Technology stack

| Layer | Technology |
|---|---|
| Backend | Laravel 12.62 |
| Language | PHP 8.3+; local environment currently uses PHP 8.5 |
| Database | MySQL in the current local and production configuration |
| Templates | Laravel Blade |
| Administration | Filament 4.11 |
| Reactive admin UI | Livewire 3.8 |
| Styling | Tailwind CSS 4 |
| Bundler | Vite 8 |
| Body typography | Inter Variable, stored locally |
| Heading typography | Montserrat Variable, stored locally |
| Testing | PHPUnit through `php artisan test` |

No frontend animation framework is installed. Public animations use CSS transforms, opacity, and the native Intersection Observer API.

Composer is configured with `config.platform.php=8.3.0`. The committed lock file is therefore resolved for the PHP 8.3 runtime available on cPanel rather than the newer local development runtime.

## 3. Main directory structure

```text
app/
├── Filament/
│   ├── Resources/             # Administration CRUD resources
│   └── Widgets/MccgStats.php  # Dashboard statistics
├── Http/Controllers/          # Public request and form logic
├── Models/                    # Eloquent domain models
└── Providers/Filament/        # Admin panel registration

database/
├── migrations/                # Database schema
└── seeders/DatabaseSeeder.php # Initial admin, SEO, services, and article

public/
├── build/                     # Compiled production assets
├── favicon.ico                # Multi-resolution MCCG browser icon
└── images/
    ├── logo.png               # Official MCCG logo
    └── hero-mccg.png          # Homepage corporate hero image

resources/
├── css/app.css                # Brand system, components, and animations
├── js/app.js                  # Menu, reveals, counters, parallax, and office carousel
└── views/
    ├── articles/
    ├── components/
    ├── layouts/
    ├── pages/
    ├── services/
    └── sitemap.blade.php

routes/web.php                 # Public application routes
config/mccg.php                # Business details and office-map configuration
tests/Feature/                 # End-to-end HTTP feature tests
DEPLOYMENT_CPANEL.md           # cPanel deployment procedure
```

## 4. Domain models and database schema

### 4.1 User

Represents a Filament administration user.

| Field | Purpose |
|---|---|
| `name` | Display name |
| `email` | Unique login email |
| `password` | Automatically hashed password |
| `role` | `admin` or `marketer` |
| `email_verified_at` | Optional verification timestamp |
| `remember_token` | Persistent login token |

Both `admin` and `marketer` roles can access the Filament panel. Access is controlled by `User::canAccessPanel()`.

Administrators manage users from the Filament `UserResource`. Marketers cannot see or access this resource. Passwords are required on creation, optional on edit, and hashed by the model's `hashed` cast. An administrator cannot delete their own account or the last remaining administrator.

### 4.2 Category

Groups editorial articles.

| Field | Purpose |
|---|---|
| `name` | Category name |
| `slug` | Unique URL identifier |
| `description` | Optional editorial description |

Relationship:

- A category `hasMany` articles.
- Deleting a category sets its articles' `category_id` to `NULL`.

### 4.3 Article

Stores blog and advisory content managed by the marketer.

| Field | Purpose |
|---|---|
| `title` | Public title |
| `slug` | Unique clean URL |
| `excerpt` | Card and page introduction |
| `content` | Rich long-form HTML content |
| `featured_image` | Public-disk image path |
| `category_id` | Optional category relationship |
| `meta_title` | Article-specific SEO title |
| `meta_description` | Article-specific SEO description |
| `keywords` | Optional comma-separated keywords |
| `status` | `draft` or `published` |
| `published_at` | Publication or scheduled-publication timestamp |
| `created_by` | Required user/author relationship |

Relationships:

- An article `belongsTo` a category.
- An article `belongsTo` a user through `created_by`.

Publishing behavior:

- If an article is saved as `published` without a date, the model assigns the current date automatically.
- The `published()` query scope only returns records where:
  - `status = published`;
  - `published_at` is not null;
  - `published_at` is in the past or equal to the current time.
- A future `published_at` date therefore schedules the article.
- Public article routes use the article slug instead of its numeric ID.
- Direct access to a draft or future article returns HTTP 404.

### 4.4 Service

Stores MCCG service pages.

| Field | Purpose |
|---|---|
| `title` | Public service title |
| `slug` | Unique clean URL |
| `short_description` | Card and hero summary |
| `content` | Full service page content |
| `icon` | Icon identifier used by the Blade service card |
| `meta_title` | Service-specific SEO title |
| `meta_description` | Service-specific SEO description |
| `is_active` | Controls public visibility |

Public behavior:

- Only active services appear on the homepage, services page, contact selector, and sitemap.
- Accessing an inactive service directly returns HTTP 404.
- Service routes use slugs.
- Supported icon identifiers currently include `calculator`, `scale`, `check`, `users`, `building`, and `chart`.

### 4.5 PageSeo

Stores editable metadata for main public pages.

| Field | Purpose |
|---|---|
| `page_name` | Human-readable admin label |
| `slug` | Internal page identifier |
| `meta_title` | Browser and search title |
| `meta_description` | Search-result description |
| `og_image` | Optional social-sharing image |

`PageSeo::for($slug)` retrieves the metadata used by a controller. Seeded identifiers are:

- `accueil`
- `a-propos`
- `services`
- `articles`
- `contact`
- `confidentialite`
- `conditions`

### 4.6 ContactMessage

Stores contact-form submissions as leads.

| Field | Purpose |
|---|---|
| `first_name` | First part of the submitted full name |
| `last_name` | Remaining part of the submitted full name |
| `email` | Required contact email |
| `phone` | Optional telephone number |
| `company` | Optional company name |
| `service` | Optional requested service |
| `message` | Required enquiry text |
| `status` | `new`, `contacted`, or `closed` |

The public form displays one `full_name` input. The controller splits the value once at the first space and stores it as `first_name` and `last_name`.

After storage, two queued mailables are dispatched:

- `NewContactMessageMail` to `CONTACT_NOTIFICATION_EMAIL`, falling back to `MAIL_FROM_ADDRESS`.
- `ContactMessageReceivedMail` to the visitor as a French receipt confirmation.

Queue-dispatch errors and final queued-job failures are logged without rolling back or breaking lead creation.

## 5. Model relationship summary

```text
User 1 ──────────── * Article
                       │
Category 1 ──────── * │

Service              Independent public content
PageSeo              Independent page metadata
ContactMessage       Independent lead record
```

## 6. Public routes

| Method | URI | Route name | Controller logic |
|---|---|---|---|
| GET | `/` | `accueil` | Homepage with six active services and three latest articles |
| GET | `/a-propos` | `a-propos` | About page and dynamic SEO |
| GET | `/services` | `services.index` | All active services |
| GET | `/services/{service}` | `services.show` | Active service detail by slug |
| GET | `/articles` | `articles.index` | Published articles, category filter, pagination by nine |
| GET | `/articles/{article}` | `articles.show` | Published article detail by slug and related content |
| GET | `/contact` | `contact` | Contact form with active-service selector |
| POST | `/contact` | `contact.store` | Validation, rate limiting, and lead creation |
| GET | `/confidentialite` | `confidentialite` | Privacy policy and dynamic SEO |
| GET | `/conditions` | `conditions` | Terms page and dynamic SEO |
| GET | `/sitemap.xml` | `sitemap` | Live XML sitemap |
| GET | `/robots.txt` | `robots` | Dynamic robots policy and sitemap URL |

The contact POST route is limited to five submissions per minute using Laravel's `throttle:5,1` middleware.

## 7. Controller and request logic

### HomeController

- Loads active services and the three latest published articles for the homepage.
- Loads page-specific SEO records.
- Renders the about, privacy, and terms pages.

### ServiceController

- Lists active services only.
- Rejects inactive service detail pages with HTTP 404.
- Supplies service-specific metadata to the detail layout.

### ArticleController

- Applies the `published()` scope to every public article query.
- Supports category filtering with `?categorie=category-slug`.
- Paginates the article archive by nine records.
- Loads only categories containing publicly published articles.
- Returns up to three related articles from the same category.

### ContactController

- Loads active services for the form selector.
- Validates name, email, phone, company, service, and message.
- Splits the full name into the database's first and last name fields.
- Creates a new message with the default `new` status.
- Queues an administrator notification and visitor confirmation independently.
- Logs mail failures without changing the successful form response.
- Redirects back with a French success message.

### SitemapController

- Produces XML dynamically from static public pages, active services, and published articles.
- New published articles appear automatically without a scheduled task.
- Generates a robots response that allows public crawling, blocks `/admin/`, and references the absolute sitemap URL.

## 8. Filament administration panel

The administration panel is mounted at:

```text
http://127.0.0.1:8000/admin
```

Authentication is provided by Filament and Laravel sessions.

### Managed resources

#### Articles

- Create, edit, search, sort, filter, and delete articles.
- Rich-text content editor.
- Featured-image upload to `storage/app/public/articles`.
- Category and author relationships.
- Draft/published status.
- Publication scheduling.
- Editable title, description, and keyword metadata.

#### Categories

- Create and edit article categories.
- Automatic slug suggestion from the category name.
- Article-count display.

#### Services

- Create and edit service pages.
- Rich-text content.
- Active/inactive visibility toggle.
- Icon identifier and service-level SEO metadata.

#### Page SEO

- Edit metadata for every principal page.
- Upload Open Graph images to `storage/app/public/seo`.

#### Contact messages

- List received enquiries.
- Copy email addresses.
- Filter by status.
- Read the original submitted details.
- Change status to new, contacted, or closed.
- Public messages cannot be created manually from Filament.
- Marketers have read-only access; administrators can update status and delete records.

#### Users

- Administrators can create and edit administrators and marketers.
- Passwords are never displayed and only change when a non-empty value is submitted.
- Users can be searched by name or email and filtered by role.
- Marketers cannot see or directly access the resource.
- Bulk deletion is disabled; self-deletion and deletion of the last administrator are blocked by policy.

### Permission matrix

| Resource | Administrator | Marketer |
|---|---|---|
| Articles | Full management | Full management |
| Categories | Full management | Full management |
| Services | Full management | View, create, and edit; no deletion |
| Page SEO | Full management | View, create, and edit; no deletion |
| Contact messages | View, update, and delete | Read-only |
| Users | Full management with deletion safeguards | No access |

### Dashboard

`MccgStats` reports:

- Number of published articles and drafts.
- Number of active services.
- Number of new contact messages.

### Local PHP `intl` fallback

Filament normally requires PHP's `intl` extension. The current Herd Lite environment does not include it.

The table definitions therefore use a graceful fallback:

- With `intl`: normal pagination and bulk deletion are enabled.
- Without `intl`: pagination and bulk actions are disabled, but listing, creation, and editing remain usable.

Production hosting should still enable `intl`.

## 9. Frontend architecture

### Layout

`resources/views/layouts/app.blade.php` is the main public layout. It provides:

- HTML language and viewport configuration.
- Dynamic title and description fallbacks.
- Canonical URLs.
- Open Graph metadata.
- Twitter card metadata.
- MCCG LocalBusiness and ProfessionalService JSON-LD.
- Vite CSS and JavaScript assets.
- Shared navbar and footer components.

### Public pages

| View | Responsibility |
|---|---|
| `pages/accueil.blade.php` | Hero, statistics, services, about, differentiators, articles, CTA |
| `pages/a-propos.blade.php` | Firm positioning, values, and working principles |
| `services/index.blade.php` | Active service grid and methodology |
| `services/show.blade.php` | Individual service content and conversion CTA |
| `articles/index.blade.php` | Category filters and article archive |
| `articles/show.blade.php` | Article content, author, Article JSON-LD, related articles |
| `pages/contact.blade.php` | Contact information, reusable form, social links, and office-map carousel |
| `pages/confidentialite.blade.php` | Privacy policy |
| `pages/conditions.blade.php` | Website terms |

### Reusable Blade components

| Component | Purpose |
|---|---|
| `navbar` | Sticky desktop and mobile navigation |
| `footer` | Brand, contact details, social profiles, legal links, and professional-services disclaimer |
| `button-primary` | Coral conversion button with animated arrow |
| `button-secondary` | Neutral outlined button |
| `section-title` | Eyebrow, title, description, and animated coral rule |
| `page-hero` | Shared internal-page header |
| `service-card` | Service summary with a field-driven line icon |
| `article-card` | Article image, category, title, excerpt, and date |
| `feature-card` | Trust and differentiator block |
| `contact-form` | Validated public enquiry form |
| `cta` | Final consultation and quote call to action |

## 10. Visual system

The public website follows the MCCG graphic charter:

| Token | Value | Usage |
|---|---|---|
| Coral | `#E84C64` | CTA buttons, links, focus, icons, and active states |
| Coral dark | `#D63B54` | Hover state |
| Charcoal | `#333333` | Main headings and footer |
| Medium gray | `#A6A6A6` | Secondary neutral color |
| Surface gray | `#F2F2F2` | Alternating page sections |
| White | `#FFFFFF` | Main backgrounds and cards |

Montserrat is used for headings and navigation. Inter is used for paragraphs, forms, and UI content. Both fonts are installed through Fontsource and compiled locally, avoiding a runtime dependency on Google Fonts.

## 11. Animation logic

Animations are intentionally implemented without a large JavaScript library.

### CSS utilities

`resources/css/app.css` defines:

- `.reveal` and `.reveal-visible` for viewport reveals.
- `.hover-lift` for cards.
- Animated navigation underlines.
- Hero title, subtitle, CTA, and image entrances.
- Section-title rule expansion.
- Button and card-arrow movement.
- Input and validation-error transitions.
- Mobile-menu fade and slide behavior.

### JavaScript behavior

`resources/js/app.js` provides:

- Sticky-navbar scrolled state.
- Accessible mobile-menu toggling.
- Body-scroll locking, Escape handling, focus restoration, and desktop-reset behavior for the mobile menu.
- Intersection Observer reveal logic.
- Automatic stagger delays for child cards.
- One-time statistic count-up animations.
- Very small desktop-only hero parallax.
- Accessible office-map carousel state, tabs, previous/next controls, and arrow-key navigation.
- A fallback that reveals everything if Intersection Observer is unavailable.

### Accessibility and performance

- Animations use `transform` and `opacity` where possible.
- Each viewport reveal runs once.
- The hero parallax is limited to approximately 12 pixels.
- `prefers-reduced-motion: reduce` disables movement and reveals content immediately.
- Content remains visible when JavaScript is unavailable because hidden reveal states are only activated after JavaScript adds `motion-ready` to the document.

## 12. SEO implementation

Every public page receives:

- A meta title.
- A meta description.
- A canonical URL.
- Open Graph title, description, URL, image, and locale.
- A Twitter summary card.

Additional structured data:

- Global LocalBusiness and ProfessionalService JSON-LD.
- Article JSON-LD on article detail pages.

The JSON-LD context key is assembled safely to avoid collision with Laravel 12's Blade `@context` directive.

Metadata priority:

1. Explicit article or service metadata.
2. The matching `PageSeo` database record.
3. MCCG defaults from the main layout.

The sitemap always reflects currently active services and currently published articles.

## 13. Contact and lead lifecycle

```text
Visitor opens /contact
        ↓
Active services populate the selector
        ↓
POST /contact with CSRF and throttle protection
        ↓
Laravel validates and normalizes the data
        ↓
ContactMessage is created with status "new"
        ↓
Administrator notification and visitor confirmation are queued
        ↓
Admin/marketer reviews it in Filament
        ↓
Status changes to "contacted" or "closed"
```

### Contact presentation and office maps

`config/mccg.php` is the single source of truth for public business details. The contact page and footer read from this configuration rather than duplicating addresses or social links in Blade.

The office section contains three slides:

- Marrakech, with the complete main-office address.
- Casablanca.
- Dubai.

Each slide has an embedded Google Map and an external “Ouvrir dans Google Maps” link. Empty map values render a professional fallback instead of causing a view error or loading an empty recursive iframe.

## 14. Initial seeded content

`DatabaseSeeder` currently creates or updates:

- The initial administrator from `ADMIN_EMAIL` and `ADMIN_PASSWORD`.
- SEO records for all main public pages.
- Six MCCG service pages.
- One initial article category.
- One sample published article.

Seeder operations use `updateOrCreate` for the initial managed content, allowing the seeder to be rerun without duplicating those records.

The seeded services are:

1. Tenue comptable.
2. Conseil fiscal.
3. Audit interne & revue comptable.
4. Gestion sociale & RH.
5. Conseil juridique et administratif.
6. Accompagnement des entreprises.

Migration `2026_07_05_000000_update_regulated_wording.php` updates existing SEO and service records to the current professional wording. Its rollback intentionally does not restore superseded marketing claims.

## 15. Environment configuration

Important variables:

```env
APP_NAME=MCCG
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000
APP_TIMEZONE=Africa/Casablanca
APP_LOCALE=fr

MCCG_PHONE="05 24 43 83 70"
MCCG_EMAIL="contact@mc-cg.com"
MCCG_ADDRESS="92, Bd Zerktouni, Appt 6, 2ème étage, Guéliz, Marrakech"
MCCG_OTHER_OFFICES="Casablanca, Dubai"
MCCG_HOURS="Lun - Ven: 9h00 - 18h00"
MCCG_LINKEDIN_URL="https://www.linkedin.com/in/majdchraibi"
MCCG_INSTAGRAM_URL="https://www.instagram.com/mccg.consulting"
MCCG_ANALYTICS_PROVIDER=google
MCCG_GOOGLE_TAG_ID=AW-987825813
MCCG_GOOGLE_TAG_MANAGER_ID=GTM-N64QTKK
MCCG_GOOGLE_ADS_CONVERSION_SEND_TO=AW-987825813/g_upCMH2m7sYEJWNhNcD
MCCG_PLAUSIBLE_DOMAIN=""
MCCG_MARRAKECH_MAPS_URL="..."
MCCG_MARRAKECH_MAPS_EMBED_URL="..."
MCCG_CASABLANCA_MAPS_URL="..."
MCCG_CASABLANCA_MAPS_EMBED_URL="..."
MCCG_DUBAI_MAPS_URL="..."
MCCG_DUBAI_MAPS_EMBED_URL="..."

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=mccg_website
DB_USERNAME=root
DB_PASSWORD=

FILESYSTEM_DISK=public

ADMIN_EMAIL=admin@mccg.ma
ADMIN_PASSWORD=replace-this-password
CONTACT_NOTIFICATION_EMAIL=admin@mccg.ma

```

Never commit a real production `.env` file or reuse the example administrator password in production.

Set `MCCG_ANALYTICS_PROVIDER` to `google` with `MCCG_GOOGLE_TAG_ID`, `MCCG_GOOGLE_TAG_MANAGER_ID`, and `MCCG_GOOGLE_ADS_CONVERSION_SEND_TO`, or to `plausible` with `MCCG_PLAUSIBLE_DOMAIN`. The Google tag and Tag Manager container use Consent Mode with advertising and analytics storage denied by default. A successful contact request emits one `mccg_generate_lead` dataLayer event and, when `MCCG_GOOGLE_ADS_CONVERSION_SEND_TO` is set, one Google Ads conversion with a `transaction_id`, both only after visitor consent. Set `MCCG_GOOGLE_ADS_CONVERSION_SEND_TO` empty when the Google Ads tag is configured in Tag Manager instead. See [GOOGLE_ADS_TRACKING.md](GOOGLE_ADS_TRACKING.md). Plausible renders without the Google cookie notice.

## 16. Local installation

### Requirements

- PHP 8.3 or newer.
- Composer 2.
- MySQL or MariaDB.
- Node.js and npm.
- PHP extensions required by Laravel and Filament, especially `intl` for the full admin-table experience.

### Commands

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm install
npm run build
composer dev
```

`composer dev` starts the Laravel development server and Vite. It excludes Laravel Pail because Windows does not provide the Unix `pcntl` extension.

To also run the queue worker:

```powershell
composer dev:queue
```

Public site:

```text
http://127.0.0.1:8000
```

Administration:

```text
http://127.0.0.1:8000/admin
```

## 17. Common operational commands

```powershell
# Apply pending database migrations
php artisan migrate

# Create/update initial managed data and the administrator
php artisan db:seed

# Clear application caches after environment changes
php artisan optimize:clear

# Rebuild public assets
npm run build

# Inspect registered routes
php artisan route:list

# Run formatting
php vendor\bin\pint

# Run tests
composer test
```

Avoid `php artisan migrate:fresh` on any database containing real data because it drops all tables.

For an existing installation, normal deployments do not require `db:seed`; the migration updates the current seeded wording. Run `db:seed` only when initializing the application or intentionally restoring the default managed content.

## 18. Testing status

The feature suite currently verifies:

- Every public page returns successfully.
- Published service and article detail pages are accessible.
- Dynamic SEO and canonical markup are present.
- Article JSON-LD is present.
- Global JSON-LD uses LocalBusiness and ProfessionalService and renders a valid schema context.
- The sitemap contains active services and published articles.
- A valid contact form creates a `new` database message.
- Both contact mailables are queued for their correct recipients.
- A mail transport or queue failure does not prevent lead storage.
- Filament login is available.
- An authenticated administrator can open every management section.
- Administrators can access user management while marketers receive HTTP 403.
- Marketer permissions match the resource matrix.
- Administrators cannot delete their own account.
- Shared mobile navigation and footer markup render correctly.
- Real contact details and social links appear on the contact page and footer.
- Contact and footer rendering remain safe when map URLs are absent.
- Marrakech, Casablanca, and Dubai map slides render with accessible carousel markup.
- Main marketing pages do not contain the prohibited regulated-status claims covered by the compliance test.
- Current compliant service names, SEO positioning, structured data, and footer disclaimer are present.
- Marrakech, Casablanca, and Dubai each render a map iframe by default.
- Empty, incomplete, Google, and Plausible analytics configurations render the correct scripts and consent UI.
- The Google Ads lead event renders only after a successful contact submission.
- The lead tracker emits once per submission, respects consent, and sends no personal data (`npm test`).

Run:

```powershell
composer test
npm test
```

Current result: **32 PHP tests passing (297 assertions) and 11 JavaScript tests passing**.

## 19. Deployment

The application is designed for a cPanel deployment with the web root pointing to Laravel's `public` directory.

The recommended split cPanel layout keeps the Git repository in `/home/USERNAME/mccg_backend` and publishes the contents of `public/` to `/home/USERNAME/public_html/mccg`. The domain document root points to `public_html/mccg`, while its customized `index.php` loads Laravel from the backend directory.

`public/build` is intentionally tracked in Git so a production pull includes the compiled Vite assets without requiring Node.js on cPanel.

The production domain is `https://mc-cg.com`; the production `.env` must therefore use `APP_URL=https://mc-cg.com`.

The complete production procedure is documented in [DEPLOYMENT_CPANEL.md](DEPLOYMENT_CPANEL.md).

The main production steps are:

```bash
composer install --no-dev --optimize-autoloader
php artisan optimize:clear
php artisan migrate --force
php artisan storage:link # first deployment only
php artisan queue:restart
php artisan optimize
```

Run `php artisan key:generate --force` only during the first installation. Replacing an established production `APP_KEY` invalidates encrypted application data and sessions. Likewise, `php artisan db:seed --force` is intended for first installation or deliberate default-content restoration, not every deployment.

The `public/build` directory must be present in production. It can be built locally with `npm run build` when Node.js is unavailable on cPanel.

## 20. Known limitations and recommended next steps

The current application is production-oriented but the following enhancements remain possible:

1. Move legal-page body content into editable page records if marketers must manage it.
2. Add image conversions and responsive WebP/AVIF variants for uploaded article images.
3. Add automated database and uploaded-file backups on the production host.
4. Validate the Google tag and lead conversion in Google Ads diagnostics after production deployment ([GOOGLE_ADS_TRACKING.md](GOOGLE_ADS_TRACKING.md)).
5. Add the full Casablanca street address (the map location is already configured).
6. Configure and monitor a persistent production queue worker for contact mail delivery.
7. Obtain a final legal review of the public wording and disclaimer before launch; the application avoids unverified regulated-status claims but repository documentation is not legal advice.

## 21. Ownership of editable content

| Content | Editable in Filament | Stored in code |
|---|---:|---:|
| Articles and article SEO | Yes | No |
| Categories | Yes | No |
| Services and service SEO | Yes | No |
| Main-page SEO | Yes | No |
| Contact-message statuses | Yes | No |
| Administrator and marketer accounts | Admin only | No |
| Homepage body copy | No | Yes |
| About-page body copy | No | Yes |
| Privacy and terms body copy | No | Yes |
| Header, footer, and global CTAs | No | Yes |
| Business contact details and map URLs | No | `config/mccg.php` / `.env` |
| Compliance disclaimer | No | Yes |

This distinction is important when planning future marketer autonomy.
