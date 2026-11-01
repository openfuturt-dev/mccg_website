<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Service;
use App\Models\User;
use App\Support\CanonicalUrl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TechnicalSeoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_sitemap_is_xml_and_contains_only_canonical_public_urls(): void
    {
        $author = User::firstOrFail();

        $publishedArticle = Article::create([
            'title' => 'Article SEO publié',
            'slug' => 'article-seo-publie',
            'excerpt' => 'Un article public utilisé pour valider le sitemap.',
            'content' => '<p>Contenu public.</p>',
            'status' => 'published',
            'published_at' => now()->subDay(),
            'created_by' => $author->id,
        ]);

        $draftArticle = Article::create([
            'title' => 'Article SEO brouillon',
            'slug' => 'article-seo-brouillon',
            'excerpt' => 'Ce brouillon ne doit pas être indexé.',
            'content' => '<p>Contenu non publié.</p>',
            'status' => 'draft',
            'created_by' => $author->id,
        ]);

        $futureArticle = Article::create([
            'title' => 'Article SEO programmé',
            'slug' => 'article-seo-programme',
            'excerpt' => 'Cet article futur ne doit pas être indexé.',
            'content' => '<p>Contenu programmé.</p>',
            'status' => 'published',
            'published_at' => now()->addDay(),
            'created_by' => $author->id,
        ]);

        $activeService = Service::create([
            'title' => 'Service SEO public',
            'slug' => 'service-seo-public',
            'short_description' => 'Un service public utilisé pour valider le sitemap.',
            'content' => '<p>Service public.</p>',
            'icon' => 'check',
            'is_active' => true,
        ]);

        $inactiveService = Service::create([
            'title' => 'Service SEO inactif',
            'slug' => 'service-seo-inactif',
            'short_description' => 'Ce service ne doit pas être indexé.',
            'content' => '<p>Service inactif.</p>',
            'icon' => 'check',
            'is_active' => false,
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8');

        $xml = simplexml_load_string($response->getContent());
        $this->assertNotFalse($xml);

        $nodes = $xml->children('http://www.sitemaps.org/schemas/sitemap/0.9')->url;
        $locations = [];

        foreach ($nodes as $node) {
            $locations[] = (string) $node->loc;
        }

        $canonical = app(CanonicalUrl::class);

        foreach ([
            'https://mc-cg.com/',
            'https://mc-cg.com/a-propos',
            'https://mc-cg.com/services',
            'https://mc-cg.com/articles',
            'https://mc-cg.com/contact',
            'https://mc-cg.com/confidentialite',
            'https://mc-cg.com/conditions',
            $canonical->route('articles.show', $publishedArticle),
            $canonical->route('services.show', $activeService),
        ] as $expectedUrl) {
            $this->assertContains($expectedUrl, $locations);
        }

        $this->assertNotContains($canonical->route('articles.show', $draftArticle), $locations);
        $this->assertNotContains($canonical->route('articles.show', $futureArticle), $locations);
        $this->assertNotContains($canonical->route('services.show', $inactiveService), $locations);

        foreach ($locations as $location) {
            $this->assertStringStartsWith(CanonicalUrl::ORIGIN, $location);
            $this->assertStringNotContainsString('www.', $location);
            $this->assertStringNotContainsString('/index.php', $location);
        }

        $response->assertSee('<lastmod>'.$publishedArticle->updated_at->toAtomString().'</lastmod>', false);
        $response->assertSee('<lastmod>'.$activeService->updated_at->toAtomString().'</lastmod>', false);
    }

    public function test_homepage_article_and_service_have_their_own_canonical_without_query_strings(): void
    {
        $article = Article::published()->firstOrFail();
        $service = Service::where('is_active', true)->firstOrFail();
        $canonical = app(CanonicalUrl::class);

        $this->get('/?utm_source=test')
            ->assertOk()
            ->assertSee('<link rel="canonical" href="https://mc-cg.com/">', false);

        $this->get(route('articles.show', $article, false).'?utm_source=test')
            ->assertOk()
            ->assertSee('<link rel="canonical" href="'.$canonical->route('articles.show', $article).'">', false);

        $this->get(route('services.show', $service, false).'?utm_source=test')
            ->assertOk()
            ->assertSee('<link rel="canonical" href="'.$canonical->route('services.show', $service).'">', false);
    }

    public function test_robots_allows_public_content_and_references_the_canonical_sitemap(): void
    {
        $response = $this->get('/robots.txt')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
            ->assertSee("User-agent: *\nAllow: /\nDisallow: /admin/", false)
            ->assertSee('Sitemap: https://mc-cg.com/sitemap.xml', false);

        $this->assertSame($response->getContent(), file_get_contents(public_path('robots.txt')));
    }

    public function test_pages_and_services_render_their_seo_keywords(): void
    {
        $this->get('/')->assertOk()->assertSee('<meta name="keywords" content="Cabinet de comptabilité au Maroc, Services comptables au Maroc', false);
        $this->get('/casablanca')->assertOk()->assertSee('Cabinet comptable à Casablanca, Cabinet de conseil à Casablanca', false);
        $this->get('/services/paie-social')->assertOk()->assertSee('<meta name="keywords" content="Gestion de la paie au Maroc', false);
        $this->get('/services/conseil-fiscal')->assertOk()->assertSee('Déclaration de TVA au Maroc', false);
    }
}
