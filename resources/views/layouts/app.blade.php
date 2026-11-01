@php
    $pageSeo = $seo ?? null;
    $title = $seoTitle ?? ($pageSeo?->meta_title ?? 'MCCG | Cabinet de conseil comptable et fiscal au Maroc');
    $description = $seoDescription ?? ($pageSeo?->meta_description ?? 'MCCG accompagne les entreprises au Maroc en tenue comptable, fiscalité, gestion sociale, conseil juridique et accompagnement administratif.');
    $keywords = $seoKeywords ?? ($pageSeo?->keywords ?: implode(', ', config('seo_keywords.default')));
    $ogImage = $seoImage ?? ($pageSeo?->og_image ? asset('storage/'.$pageSeo->og_image) : asset('images/logo.png'));
    $schemaContext = '@'.'context';
    $analyticsProvider = config('mccg.analytics_provider');
    $googleTagId = config('mccg.google_tag_id');
    $googleTagManagerId = config('mccg.google_tag_manager_id');
    $googleAdsConversionSendTo = config('mccg.google_ads_conversion_send_to');
    $plausibleDomain = config('mccg.plausible_domain');
    $canonicalUrl = app(\App\Support\CanonicalUrl::class)->current(request());
@endphp
<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    <meta name="description" content="{{ $description }}">
    @if($keywords)<meta name="keywords" content="{{ $keywords }}">@endif
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <meta property="og:locale" content="fr_MA">
    <meta property="og:type" content="{{ $ogType ?? 'website' }}">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:site_name" content="MCCG">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="theme-color" content="#333333">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <script>
        // Lets CSS hide animated elements before first paint; reveals everything if the bundle fails to start.
        document.documentElement.classList.add('js');
        setTimeout(function () { if (!document.documentElement.classList.contains('motion-ready')) document.documentElement.classList.remove('js'); }, 2500);
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script type="application/ld+json">{!! json_encode([
        $schemaContext => 'https://schema.org', '@type' => ['LocalBusiness', 'ProfessionalService'],
        'name' => 'MCCG', 'url' => \App\Support\CanonicalUrl::ORIGIN.'/', 'logo' => asset('images/logo.png'),
        'description' => 'MCCG est un cabinet de conseil comptable, fiscal, social et administratif basé à Marrakech, accompagnant les entreprises et entrepreneurs au Maroc.',
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => config('mccg.street_address'),
            'addressLocality' => config('mccg.city'),
            'addressRegion' => config('mccg.region'),
            'addressCountry' => 'MA',
        ],
        'areaServed' => ['@type' => 'Country', 'name' => 'Maroc'],
        'knowsAbout' => ['Comptabilité', 'Externalisation comptable', 'Fiscalité des entreprises marocaines', 'Gestion de la paie', 'Contrôle de gestion', 'Gestion de trésorerie', 'Création d’entreprise au Maroc', 'Contrôle interne', 'Conseil en management'],
        'email' => config('mccg.email'), 'telephone' => config('mccg.phone'),
        'sameAs' => array_values(array_filter([config('mccg.linkedin_url'), config('mccg.instagram_url')])),
        'priceRange' => '$$',
    ], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}</script>
    @stack('structured-data')
    @if($analyticsProvider === 'google' && $googleTagId)
        <script data-mccg-google-bootstrap>
            (() => {
                const measurementId = @json($googleTagId);
                const consentState = {
                    granted: {
                        ad_storage: 'granted',
                        ad_user_data: 'granted',
                        ad_personalization: 'granted',
                        analytics_storage: 'granted',
                    },
                    denied: {
                        ad_storage: 'denied',
                        ad_user_data: 'denied',
                        ad_personalization: 'denied',
                        analytics_storage: 'denied',
                    },
                };

                window.dataLayer = window.dataLayer || [];
                window.gtag = window.gtag || function () { window.dataLayer.push(arguments); };
                window.gtag('consent', 'default', consentState.denied);

                let savedConsent = null;
                try {
                    savedConsent = window.localStorage.getItem('mccg_analytics_consent');
                } catch (_) {
                    // Consent remains denied if browser storage is unavailable.
                }

                window.mccgGoogleConsent = savedConsent;
                if (savedConsent === 'accepted') {
                    window.gtag('consent', 'update', consentState.granted);
                }

                window.mccgSetGoogleConsent = (status) => {
                    window.mccgGoogleConsent = status;
                    window.gtag('consent', 'update', status === 'accepted' ? consentState.granted : consentState.denied);
                };

                window.gtag('js', new Date());
                window.gtag('config', measurementId);
            })();
        </script>
        @if($googleTagManagerId)
            <!-- Google Tag Manager -->
            <script data-mccg-google-tag-manager>
                (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
                })(window,document,'script','dataLayer',@json($googleTagManagerId));
            </script>
            <!-- End Google Tag Manager -->
        @endif
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ urlencode($googleTagId) }}" data-mccg-google-tag></script>
        @if(session('mccg_lead_id'))
            <script type="application/json" data-mccg-lead>@json(['id' => session('mccg_lead_id'), 'form' => 'contact', 'sendTo' => $googleAdsConversionSendTo ?: null])</script>
        @endif
    @elseif($analyticsProvider === 'plausible' && $plausibleDomain)
        <script defer data-domain="{{ $plausibleDomain }}" src="https://plausible.io/js/script.js" data-mccg-plausible></script>
    @endif
</head>
<body class="bg-white text-slate-700 antialiased">
    <a href="#contenu" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] focus:rounded-md focus:bg-charcoal focus:px-4 focus:py-3 focus:text-sm focus:font-semibold focus:text-white">Aller au contenu</a>
    @if($analyticsProvider === 'google' && $googleTagId && $googleTagManagerId)
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ urlencode($googleTagManagerId) }}"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
    @endif
    <x-navbar />

    <main id="contenu">@yield('content')</main>

    <x-footer />

    @if($analyticsProvider === 'google' && $googleTagId)
        <x-cookie-notice />
    @endif
    @stack('scripts')
</body>
</html>
