@php
    $phone = config('mccg.phone');
    $email = config('mccg.email');
    $sites = [
        ['route' => 'marrakech', 'city' => 'Marrakech', 'label' => 'Bureau principal · Maroc', 'image' => 'marrakech-thumb.webp'],
        ['route' => 'casablanca', 'city' => 'Casablanca', 'label' => 'Maroc', 'image' => 'casa-thumb.webp'],
        ['route' => 'dubai', 'city' => 'Dubaï', 'label' => 'Émirats arabes unis', 'image' => 'dubai-thumb.webp'],
    ];
@endphp

{{-- Utility bar (desktop): scrolls away, the main header stays sticky. --}}
<div class="hidden bg-charcoal text-[12px] text-white/70 lg:block">
    <div class="container-site flex h-10 items-center justify-between gap-6">
        <p class="flex items-center gap-2"><x-line-icon name="map-pin" class="size-3.5 text-coral" /> Marrakech · Casablanca · Dubaï</p>
        <div class="flex items-center gap-6">
            <a class="flex items-center gap-2 hover:text-white" href="tel:{{ preg_replace('/\s+/', '', $phone) }}"><x-line-icon name="phone" class="size-3.5 text-coral" />{{ $phone }}</a>
            <a class="flex items-center gap-2 hover:text-white" href="mailto:{{ $email }}"><x-line-icon name="mail" class="size-3.5 text-coral" />{{ $email }}</a>
            <span class="flex items-center gap-2"><x-line-icon name="clock" class="size-3.5 text-coral" />{{ config('mccg.hours') }}</span>
            <span class="h-4 w-px bg-white/15" aria-hidden="true"></span>
            <a class="hover:text-coral" href="{{ config('mccg.linkedin_url') }}" target="_blank" rel="noopener noreferrer" aria-label="MCCG sur LinkedIn"><x-line-icon name="linkedin" class="size-4" /></a>
            <a class="hover:text-coral" href="{{ config('mccg.instagram_url') }}" target="_blank" rel="noopener noreferrer" aria-label="MCCG sur Instagram"><x-line-icon name="instagram" class="size-4" /></a>
        </div>
    </div>
</div>

<header class="site-navbar sticky top-0 z-50 border-b border-transparent bg-white" data-navbar>
    <div class="navbar-row container-site flex h-16 items-center justify-between sm:h-20">
        <a href="{{ route('accueil') }}" class="flex shrink-0 items-center" aria-label="MCCG — Accueil">
            <img src="{{ asset('images/logo-mccg.png') }}" alt="MCCG" class="h-9 w-auto sm:h-10" width="233" height="56">
        </a>

        <nav class="hidden items-center gap-8 text-[13.5px] font-semibold text-slate-600 lg:flex" aria-label="Navigation principale">
            <a class="nav-link {{ request()->routeIs('accueil') ? 'active' : '' }}" href="{{ route('accueil') }}">Accueil</a>
            <a class="nav-link {{ request()->routeIs('a-propos') ? 'active' : '' }}" href="{{ route('a-propos') }}">À propos</a>
            <a class="nav-link {{ request()->routeIs('services.*') ? 'active' : '' }}" href="{{ route('services.index') }}">Services</a>
            <a class="nav-link {{ request()->routeIs('articles.*') ? 'active' : '' }}" href="{{ route('articles.index') }}">Articles</a>
            <div class="office-menu" data-office-menu>
                <button type="button" class="nav-link office-menu__trigger {{ request()->routeIs('marrakech', 'casablanca', 'dubai') ? 'active' : '' }}" aria-haspopup="true" aria-expanded="false" aria-controls="office-menu-panel" data-office-trigger>
                    Nos sites <x-line-icon name="chevron-down" class="office-menu__chevron" stroke="2" />
                </button>
                <div id="office-menu-panel" class="office-menu__panel" aria-label="Choisir un site">
                    <p class="px-2 pb-2 pt-1 text-[10px] font-semibold uppercase tracking-[.2em] text-slate-400">Nos implantations</p>
                    @foreach($sites as $site)
                        <a class="office-menu__link {{ request()->routeIs($site['route']) ? 'is-active' : '' }}" href="{{ route($site['route']) }}">
                            <img src="{{ asset('images/'.$site['image']) }}" alt="" width="48" height="48" loading="lazy" decoding="async">
                            <span class="min-w-0 grow">
                                <span class="office-menu__city block font-heading text-sm font-bold">{{ $site['city'] }}</span>
                                <span class="block text-xs font-medium text-slate-500">{{ $site['label'] }}</span>
                            </span>
                            <x-line-icon name="arrow-up-right" class="size-4 text-slate-400" />
                        </a>
                    @endforeach
                </div>
            </div>
            <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a>
            <x-button-primary :href="route('contact', ['objet' => 'consultation'])" class="!min-h-11 !px-5">Nous consulter</x-button-primary>
        </nav>

        <button id="menu-toggle" type="button" class="grid size-11 place-items-center rounded-full border border-line text-charcoal transition hover:border-coral hover:text-coral lg:hidden" aria-label="Ouvrir le menu" aria-expanded="false" aria-controls="mobile-menu">
            <x-line-icon name="menu" class="menu-icon-open size-5" stroke="1.8" />
            <x-line-icon name="x" class="menu-icon-close size-5" stroke="1.8" />
        </button>
    </div>

    <span class="scroll-progress absolute inset-x-0 bottom-0 block h-0.5 bg-coral" data-scroll-progress aria-hidden="true"></span>

    <nav id="mobile-menu" class="mobile-menu-panel absolute inset-x-0 top-full border-t border-line bg-white shadow-lift lg:hidden" aria-label="Navigation mobile" aria-hidden="true">
        <div class="container-site flex max-h-[calc(100dvh-4rem)] flex-col overflow-y-auto py-5 sm:max-h-[calc(100dvh-5rem)]">
            <div class="flex flex-col font-heading text-lg font-semibold text-charcoal">
                @foreach([['accueil', 'Accueil', 'accueil'], ['a-propos', 'À propos', 'a-propos'], ['services.index', 'Services', 'services.*'], ['articles.index', 'Articles', 'articles.*'], ['contact', 'Contact', 'contact']] as [$route, $label, $pattern])
                    <a class="flex min-h-12 items-center justify-between border-b border-line/70 {{ request()->routeIs($pattern) ? 'text-coral' : 'hover:text-coral' }}" href="{{ route($route) }}">{{ $label }} <x-line-icon name="arrow-right" class="size-4 text-slate-300" /></a>
                @endforeach
            </div>
            <p class="pb-3 pt-6 text-[10px] font-semibold uppercase tracking-[.2em] text-slate-400">Nos sites</p>
            <div class="grid grid-cols-3 gap-2">
                @foreach($sites as $site)
                    <a class="group relative overflow-hidden rounded-lg" href="{{ route($site['route']) }}">
                        <img src="{{ asset('images/'.$site['image']) }}" alt="" class="img-zoom aspect-[4/3] w-full object-cover" width="160" height="120" loading="lazy" decoding="async">
                        <span class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></span>
                        <span class="absolute inset-x-0 bottom-0 p-2 text-center text-xs font-semibold text-white">{{ $site['city'] }}</span>
                    </a>
                @endforeach
            </div>
            <div class="mt-6 grid gap-2 text-sm text-slate-600">
                <a class="flex items-center gap-3" href="tel:{{ preg_replace('/\s+/', '', $phone) }}"><x-line-icon name="phone" class="size-4 text-coral" />{{ $phone }}</a>
                <a class="flex items-center gap-3 break-all" href="mailto:{{ $email }}"><x-line-icon name="mail" class="size-4 text-coral" />{{ $email }}</a>
            </div>
            <x-button-primary :href="route('contact', ['objet' => 'consultation'])" class="mt-6 w-full">Nous consulter</x-button-primary>
        </div>
    </nav>
</header>
