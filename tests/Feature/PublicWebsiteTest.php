<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Service;
use App\Models\User;
use App\Support\CanonicalUrl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Tests\TestCase;

class PublicWebsiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_public_pages_are_available(): void
    {
        foreach (['/', '/a-propos', '/services', '/articles', '/contact', '/confidentialite', '/conditions', '/sitemap.xml', '/robots.txt'] as $uri) {
            $this->get($uri)->assertOk();
        }
    }

    public function test_shared_mobile_navigation_and_footer_render(): void
    {
        $this->get(route('accueil'))
            ->assertOk()
            ->assertSee('id="menu-toggle"', false)
            ->assertSee('aria-controls="mobile-menu"', false)
            ->assertSee('id="mobile-menu"', false)
            ->assertSee('Nous consulter')
            ->assertSee('Confidentialité')
            ->assertSee('Conditions');
    }

    public function test_main_marketing_pages_do_not_claim_regulated_accounting_status(): void
    {
        $riskyClaims = [
            'cabinet d’expertise comptable',
            'expert-comptable',
            'commissariat aux comptes',
            'certification des comptes',
            'audit légal',
            'membre de l’ordre',
        ];

        foreach (['/', '/a-propos', '/services', '/contact'] as $uri) {
            $content = mb_strtolower($this->get($uri)->assertOk()->getContent());

            foreach ($riskyClaims as $claim) {
                $this->assertStringNotContainsString($claim, $content, "La page {$uri} contient l’allégation réglementée « {$claim} ».");
            }
        }
    }

    public function test_public_copy_uses_compliant_positioning_and_disclaimer(): void
    {
        $this->get(route('accueil'))
            ->assertSeeText('Votre partenaire de confiance en conseil comptable, fiscal et social')
            ->assertSee('MCCG | Cabinet de conseil comptable et fiscal au Maroc', false)
            ->assertSee('"@context":"https://schema.org"', false)
            ->assertSee('"@type":["LocalBusiness","ProfessionalService"]', false)
            ->assertSee('Les prestations réglementées sont réalisées uniquement lorsqu’elles sont légalement autorisées');

        $this->get(route('services.index'))
            ->assertSee('Tenue comptable')
            ->assertSee('Conseil fiscal')
            ->assertSee('Audit interne &amp; revue comptable', false)
            ->assertSee('Conseil juridique et administratif');
    }

    public function test_published_content_is_publicly_accessible(): void
    {
        $service = Service::where('is_active', true)->first();
        $article = Article::published()->first();

        $this->get(route('services.show', $service))
            ->assertOk()
            ->assertSee($service->meta_title, false)
            ->assertSee('rel="canonical"', false);
        $this->get(route('articles.show', $article))
            ->assertOk()
            ->assertSee($article->meta_title, false)
            ->assertSee('application/ld+json', false)
            ->assertSee('"@context":"https://schema.org"', false);
        $this->get('/sitemap.xml')
            ->assertSee(app(CanonicalUrl::class)->route('services.show', $service), false)
            ->assertSee(app(CanonicalUrl::class)->route('articles.show', $article), false);
    }

    public function test_contact_form_stores_a_message(): void
    {
        config()->set('services.resend.key', 're_test_secret');
        config()->set('services.resend.contact.to', 'contact@mc-cg.com');
        config()->set('services.resend.contact.from_email', 'noreply@mc-cg.com');
        config()->set('services.resend.contact.from_name', 'MCCG Website');
        Http::fake([
            'api.resend.com/*' => Http::response(['id' => 'email_123'], 200),
        ]);

        $response = $this->post(route('contact.store'), [
            'full_name' => 'Samira Alaoui', 'email' => 'samira@example.com',
            'phone' => '+212600000000', 'company' => 'Atlas SARL', 'service' => 'Conseil fiscal',
            'message' => 'Je souhaite échanger au sujet de notre organisation fiscale.',
        ]);

        $response
            ->assertSessionHas('success')
            ->assertSessionHas('mccg_lead_id', fn ($id) => Str::isUuid($id));

        $this->assertDatabaseHas('contact_messages', ['email' => 'samira@example.com', 'status' => 'new']);
        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.resend.com/emails'
                && $request->hasHeader('Authorization', 'Bearer re_test_secret')
                && $request['from'] === 'MCCG Website <noreply@mc-cg.com>'
                && $request['to'] === ['contact@mc-cg.com']
                && $request['reply_to'] === 'samira@example.com'
                && str_contains($request['html'], 'Samira Alaoui');
        });
    }

    public function test_contact_form_validates_required_fields_and_email(): void
    {
        Http::fake();
        $this->post(route('contact.store'), [])->assertSessionHasErrors(['full_name', 'email', 'message']);
        $this->post(route('contact.store'), [
            'full_name' => 'Test Client',
            'email' => 'not-an-email',
            'message' => 'Un message suffisamment long.',
        ])->assertSessionHasErrors(['email']);
        $this->assertFalse(session()->has('mccg_lead_id'));
        Http::assertNothingSent();
    }

    public function test_contact_page_does_not_render_recaptcha(): void
    {
        $this->get(route('contact'))
            ->assertOk()
            ->assertDontSee('g-recaptcha', false)
            ->assertDontSee('google.com/recaptcha', false)
            ->assertDontSee('Vérification anti-robot');
    }

    public function test_privacy_page_uses_the_dedicated_privacy_email(): void
    {
        config()->set('mccg.privacy_email', 'contact@mc-cg.com');

        $this->get(route('confidentialite'))
            ->assertOk()
            ->assertSee('href="mailto:contact@mc-cg.com"', false)
            ->assertSee('contact@mc-cg.com');
    }

    public function test_contact_form_is_rate_limited(): void
    {
        Http::fake();

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post(route('contact.store'), [])->assertRedirect();
        }

        $this->post(route('contact.store'), [])->assertTooManyRequests();
        Http::assertNothingSent();
    }

    public function test_contact_page_displays_configured_business_details_and_structured_data(): void
    {
        $response = $this->get(route('contact'));

        $response->assertOk()
            ->assertSee('05 24 43 83 70')
            ->assertSee('contact@mc-cg.com')
            ->assertSee('92, Bd Zerktouni, Appt 6, 2ème étage, Guéliz, Marrakech')
            ->assertSee('Casablanca, Dubai')
            ->assertSee('Ouvrir dans Google Maps')
            ->assertSee('Carte du bureau MCCG à Marrakech')
            ->assertSee('Carte du bureau MCCG à Dubai')
            ->assertSee('Carte du bureau MCCG à Casablanca')
            ->assertSee('33.5846519,-7.6528649', false)
            ->assertDontSee('data-map-fallback="Casablanca"', false)
            ->assertSee('data-office-carousel', false)
            ->assertSee('"telephone":"05 24 43 83 70"', false)
            ->assertSee('"email":"contact@mc-cg.com"', false)
            ->assertSee('"streetAddress":"92, Bd Zerktouni, Appt 6, 2ème étage"', false)
            ->assertSee('"addressLocality":"Marrakech"', false);

        $this->assertSame(3, substr_count($response->getContent(), 'title="Carte du bureau'));
    }

    public function test_each_city_page_embeds_its_own_office_map(): void
    {
        $this->get(route('marrakech'))->assertOk()->assertSee('31.6372705,-8.0116213', false);
        $this->get(route('casablanca'))->assertOk()
            ->assertSee('33.5846519,-7.6528649', false)
            ->assertDontSee('31.6372705', false);
        $this->get(route('dubai'))->assertOk()
            ->assertSee('25.1773294,55.273418', false)
            ->assertDontSee('31.6372705', false);
    }

    public function test_analytics_are_not_rendered_without_configuration(): void
    {
        config()->set('mccg.analytics_provider');
        config()->set('mccg.google_tag_id');
        config()->set('mccg.google_tag_manager_id');
        config()->set('mccg.google_ads_conversion_send_to');
        config()->set('mccg.plausible_domain');

        $this->get(route('accueil'))
            ->assertOk()
            ->assertDontSee('googletagmanager.com', false)
            ->assertDontSee('plausible.io/js/script.js', false)
            ->assertDontSee('data-cookie-notice', false);
    }

    public function test_google_tag_and_cookie_notice_require_provider_and_id(): void
    {
        config()->set('mccg.analytics_provider', 'google');
        config()->set('mccg.google_tag_id', 'AW-987825813');
        config()->set('mccg.google_tag_manager_id', 'GTM-N64QTKK');
        config()->set('mccg.plausible_domain', 'wrong.example');

        $this->get(route('accueil'))
            ->assertOk()
            ->assertSee('src="https://www.googletagmanager.com/gtag/js?id=AW-987825813"', false)
            ->assertSee("(window,document,'script','dataLayer',\"GTM-N64QTKK\")", false)
            ->assertSee('src="https://www.googletagmanager.com/ns.html?id=GTM-N64QTKK"', false)
            ->assertSee('data-mccg-google-bootstrap', false)
            ->assertSee("window.gtag('consent', 'default'", false)
            ->assertSee('data-cookie-notice', false)
            ->assertDontSee('plausible.io/js/script.js', false);
    }

    public function test_incomplete_analytics_configuration_renders_nothing(): void
    {
        config()->set('mccg.analytics_provider', 'google');
        config()->set('mccg.google_tag_id');

        $this->get(route('accueil'))
            ->assertDontSee('googletagmanager.com', false)
            ->assertDontSee('data-cookie-notice', false);

        config()->set('mccg.analytics_provider', 'plausible');
        config()->set('mccg.plausible_domain');

        $this->get(route('accueil'))
            ->assertDontSee('plausible.io/js/script.js', false);
    }

    public function test_plausible_analytics_requires_provider_and_domain(): void
    {
        config()->set('mccg.analytics_provider', 'plausible');
        config()->set('mccg.plausible_domain', 'mc-cg.com');
        config()->set('mccg.google_tag_id', 'G-WRONG');

        $this->get(route('accueil'))
            ->assertOk()
            ->assertSee('src="https://plausible.io/js/script.js"', false)
            ->assertSee('data-domain="mc-cg.com"', false)
            ->assertSee('data-mccg-plausible', false)
            ->assertDontSee('googletagmanager.com', false)
            ->assertDontSee('data-cookie-notice', false);
    }

    public function test_lead_payload_is_rendered_only_for_a_successful_lead(): void
    {
        config()->set('mccg.analytics_provider', 'google');
        config()->set('mccg.google_tag_id', 'AW-987825813');
        config()->set('mccg.google_ads_conversion_send_to', 'AW-987825813/g_upCMH2m7sYEJWNhNcD');
        $leadId = (string) Str::uuid();

        $this->get(route('contact'))
            ->assertOk()
            ->assertDontSee('data-mccg-lead', false)
            ->assertDontSee("window.gtag('event', 'conversion'", false);

        $this->withSession(['mccg_lead_id' => $leadId])
            ->get(route('contact'))
            ->assertOk()
            ->assertSee('<script type="application/json" data-mccg-lead>', false)
            ->assertSee('"id":"'.$leadId.'"', false)
            ->assertSee('"form":"contact"', false)
            ->assertSee('"sendTo":"AW-987825813\/g_upCMH2m7sYEJWNhNcD"', false);
    }

    public function test_lead_payload_supports_gtm_only_conversion_setup(): void
    {
        config()->set('mccg.analytics_provider', 'google');
        config()->set('mccg.google_tag_id', 'AW-987825813');
        config()->set('mccg.google_ads_conversion_send_to', '');

        $this->withSession(['mccg_lead_id' => (string) Str::uuid()])
            ->get(route('contact'))
            ->assertOk()
            ->assertSee('data-mccg-lead', false)
            ->assertSee('"sendTo":null', false);
    }

    public function test_lead_payload_is_not_rendered_without_google_tracking(): void
    {
        config()->set('mccg.analytics_provider', 'plausible');
        config()->set('mccg.plausible_domain', 'mc-cg.com');

        $this->withSession(['mccg_lead_id' => (string) Str::uuid()])
            ->get(route('contact'))
            ->assertOk()
            ->assertDontSee('data-mccg-lead', false);
    }

    public function test_successful_lead_is_rendered_once_without_personal_data(): void
    {
        config()->set('mccg.analytics_provider', 'google');
        config()->set('mccg.google_tag_id', 'AW-987825813');
        config()->set('services.resend.key', 're_test_secret');
        config()->set('services.resend.contact.to', 'contact@mc-cg.com');
        Http::fake(['api.resend.com/*' => Http::response(['id' => 'email_123'], 200)]);

        $this->from(route('contact'))->followingRedirects()->post(route('contact.store'), [
            'full_name' => 'Samira Alaoui', 'email' => 'samira@example.com',
            'phone' => '+212600000000', 'company' => 'Atlas SARL',
            'message' => 'Je souhaite échanger au sujet de notre organisation fiscale.',
        ])->assertOk()->assertSee('Votre demande a bien été envoyée.');

        $html = $this->get(route('contact'))->assertOk()->getContent();
        $this->assertStringNotContainsString('data-mccg-lead', $html, 'A reload must not report the lead again.');

        $first = $this->postLeadAndCapturePayload();
        $second = $this->postLeadAndCapturePayload();
        $this->assertNotSame($first['id'], $second['id'], 'Each submission needs its own transaction ID.');

        foreach (['Samira', 'samira@example.com', '+212600000000', 'Atlas SARL', 'organisation fiscale'] as $personalData) {
            $this->assertStringNotContainsString($personalData, json_encode($first));
        }
    }

    public function test_contact_page_and_footer_work_without_google_maps_urls(): void
    {
        config()->set('mccg.google_maps_url');
        config()->set('mccg.google_maps_embed_url');
        config()->set('mccg.office_locations', [[
            'city' => 'Marrakech',
            'address' => config('mccg.address'),
            'maps_url' => null,
            'embed_url' => null,
        ]]);

        $this->get(route('contact'))
            ->assertOk()
            ->assertSee('MCCG Marrakech')
            ->assertSee('Ouvrir dans Google Maps')
            ->assertSee('05 24 43 83 70')
            ->assertSee('contact@mc-cg.com')
            ->assertSee('Autres bureaux');
    }

    public function test_footer_displays_configured_contact_details(): void
    {
        $footer = Blade::render('<x-footer />');

        $this->assertStringContainsString('05 24 43 83 70', $footer);
        $this->assertStringContainsString('contact@mc-cg.com', $footer);
        $this->assertStringContainsString('92, Bd Zerktouni, Appt 6, 2ème étage, Guéliz, Marrakech', $footer);
        $this->assertStringContainsString('Casablanca, Dubai', $footer);
        $this->assertStringContainsString('https://www.linkedin.com/in/majdchraibi', $footer);
        $this->assertStringContainsString('https://www.instagram.com/mccg.consulting', $footer);
    }

    public function test_admin_login_is_available(): void
    {
        $this->get('/admin/login')->assertOk();
        $this->assertTrue(User::first()->canAccessPanel(filament()->getPanel('admin')));
    }

    public function test_contact_reports_a_safe_error_when_resend_fails(): void
    {
        config()->set('services.resend.key', 're_private_key');
        config()->set('services.resend.contact.to', 'contact@mc-cg.com');
        Http::fake([
            'api.resend.com/*' => Http::response(['message' => 'Provider diagnostics'], 500),
        ]);

        $this->post(route('contact.store'), [
            'full_name' => 'Youssef Amrani',
            'email' => 'youssef@example.com',
            'message' => 'Je souhaite être accompagné pour la création de mon entreprise.',
        ])
            ->assertSessionHas('error')
            ->assertSessionHasInput('email', 'youssef@example.com')
            ->assertSessionMissing('mccg_lead_id');

        $this->assertDatabaseHas('contact_messages', ['email' => 'youssef@example.com', 'status' => 'new']);
        $this->assertStringNotContainsString('re_private_key', session('error'));
        $this->assertStringNotContainsString('Provider diagnostics', session('error'));
    }

    private function postLeadAndCapturePayload(): array
    {
        $html = $this->from(route('contact'))->followingRedirects()->post(route('contact.store'), [
            'full_name' => 'Samira Alaoui', 'email' => 'samira@example.com',
            'phone' => '+212600000000', 'company' => 'Atlas SARL',
            'message' => 'Je souhaite échanger au sujet de notre organisation fiscale.',
        ])->assertOk()->getContent();

        $this->assertSame(1, preg_match('#<script type="application/json" data-mccg-lead>(.*?)</script>#s', $html, $match));

        return json_decode($match[1], true, flags: JSON_THROW_ON_ERROR);
    }

    public function test_admin_can_open_all_management_sections(): void
    {
        $this->actingAs(User::first());

        foreach (['/admin', '/admin/articles', '/admin/categories', '/admin/services', '/admin/page-seos', '/admin/contact-messages'] as $uri) {
            $this->get($uri)->assertOk();
        }
    }
}
