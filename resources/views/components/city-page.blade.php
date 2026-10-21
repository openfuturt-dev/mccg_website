{{-- Shared layout for the Marrakech, Casablanca and Dubai landing pages. --}}
@props([
    'route',
    'city',
    'officeCity',
    'image',
    'imageAlt',
    'imageWidth',
    'imageHeight',
    'imageSrcset' => null,
    'region',
    'badges' => [],
    'aboutLead',
    'aboutBody',
    'mapHeading',
    'address',
    'services',
])
@php
    $office = collect(config('mccg.office_locations', []))->firstWhere('city', $officeCity) ?? [];
    $mapsUrl = $office['maps_url'] ?? null;
    $embedUrl = $office['embed_url'] ?? null;
    $phone = config('mccg.phone');
    $email = config('mccg.email');
@endphp

<div class="bg-white text-charcoal">
    {{-- Hero. --}}
    <section class="relative isolate flex min-h-[620px] items-end overflow-hidden bg-ink text-white lg:min-h-[720px]">
        <div class="absolute inset-0 -z-10" data-reveal-image data-hero-image>
            <img src="{{ asset('images/'.$image) }}" srcset="{{ $imageSrcset ?: asset('images/'.$image).' '.$imageWidth.'w' }}" sizes="100vw" alt="{{ $imageAlt }}" class="h-full w-full object-cover" width="{{ $imageWidth }}" height="{{ $imageHeight }}" fetchpriority="high" data-parallax>
        </div>
        <div class="absolute inset-0 -z-10 bg-gradient-to-r from-black/80 via-black/55 to-black/10" aria-hidden="true"></div>
        <div class="absolute inset-x-0 bottom-0 -z-10 h-1/2 bg-gradient-to-t from-black/60 to-transparent" aria-hidden="true"></div>

        <div class="container-site pb-14 pt-24 sm:pb-20 lg:pb-24">
            <div class="max-w-3xl">
                <p class="eyebrow" data-hero>Cabinet de conseil à {{ $city }}</p>
                <h1 class="display-title mt-6 break-words !text-white" data-split>Votre partenaire de confiance pour <span class="highlight-underline text-coral">décider avec clarté.</span></h1>
                <p class="mt-7 max-w-2xl text-base leading-7 text-white/75 sm:text-lg sm:leading-8" data-hero>MCCG {{ $city }} accompagne les entreprises, entrepreneurs et investisseurs en comptabilité, fiscalité, gestion sociale, conseil juridique et démarches administratives.</p>
                <div class="mt-9 flex flex-col gap-3 sm:flex-row" data-hero>
                    <x-button-primary :href="route('services.index')" class="w-full sm:w-auto">Découvrir nos services</x-button-primary>
                    <a href="{{ route('contact') }}" class="btn-outline-light w-full sm:w-auto">Nous contacter</a>
                </div>
            </div>
            <div class="mt-12 flex flex-col gap-4 border-t border-white/15 pt-6 text-sm text-white/70 sm:flex-row sm:items-center sm:justify-between" data-hero>
                <ul class="flex flex-wrap gap-x-7 gap-y-2">
                    @foreach($badges as $badge)
                        <li class="flex items-center gap-2"><x-line-icon name="check" class="size-4 text-coral" stroke="2.4" />{{ $badge }}</li>
                    @endforeach
                </ul>
                <p class="flex items-center gap-2 font-medium text-white"><x-line-icon name="map-pin" class="size-4 text-coral" />{{ $region }}</p>
            </div>
        </div>
    </section>

    {{-- About. --}}
    <section class="section-space overflow-hidden bg-white">
        <div class="container-site grid gap-10 lg:grid-cols-12 lg:gap-8">
            <div class="lg:col-span-3"><p class="eyebrow" data-reveal>À propos de MCCG {{ $city }}</p></div>
            <div class="lg:col-span-9">
                <h2 class="max-w-5xl font-heading text-3xl font-bold leading-[1.12] tracking-[-.03em] text-charcoal sm:text-4xl lg:text-5xl" data-reveal>Nous rendons les sujets complexes plus simples, pour que chaque décision soit prise avec <span class="text-coral">confiance.</span></h2>
                <div class="mt-12 grid gap-8 border-t border-line pt-10 sm:grid-cols-2 sm:gap-12">
                    <p class="text-base leading-8 text-slate-600" data-reveal>{{ $aboutLead }}</p>
                    <div data-reveal>
                        <p class="text-base leading-8 text-slate-600">{{ $aboutBody }}</p>
                        <a href="{{ route('a-propos') }}" class="link-arrow group mt-7">Découvrir le cabinet <x-line-icon name="arrow-right" class="btn-arrow" stroke="2" /></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Expertise index. --}}
    <section class="section-space bg-mist">
        <div class="container-site">
            <div class="grid gap-8 lg:grid-cols-12 lg:items-end">
                <div class="lg:col-span-7" data-reveal>
                    <p class="eyebrow">Nos expertises</p>
                    <h2 class="section-title mt-5 max-w-2xl">Un accompagnement complet, construit autour de votre réalité.</h2>
                </div>
                <p class="max-w-xl text-base leading-7 text-slate-600 lg:col-span-5 lg:justify-self-end" data-reveal>Un interlocuteur attentif, des expertises coordonnées et des recommandations directement utiles à votre organisation.</p>
            </div>

            <div class="mt-12 grid gap-px overflow-hidden rounded-2xl border border-line bg-line sm:mt-16 md:grid-cols-2 lg:grid-cols-3" data-stagger>
                @forelse($services as $service)
                    <a href="{{ route('services.show', $service) }}" class="group relative min-h-64 overflow-hidden bg-white p-7 transition-colors duration-300 hover:bg-charcoal sm:p-9" data-reveal>
                        <span class="absolute inset-x-0 top-0 h-0.5 origin-left scale-x-0 bg-coral transition-transform duration-300 ease-premium group-hover:scale-x-100" aria-hidden="true"></span>
                        <div class="flex items-center justify-between">
                            <span class="font-heading text-xs font-semibold tracking-[.18em] text-coral">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="grid size-10 place-items-center rounded-full border border-line text-charcoal transition duration-300 ease-premium group-hover:rotate-45 group-hover:border-coral group-hover:bg-coral group-hover:text-white" aria-hidden="true"><x-line-icon name="arrow-up-right" class="size-4" stroke="2" /></span>
                        </div>
                        <h3 class="mt-12 font-heading text-xl font-bold text-charcoal transition-colors duration-300 group-hover:text-white sm:text-2xl">{{ $service->title }}</h3>
                        <p class="mt-4 max-w-xs text-sm leading-7 text-slate-500 transition-colors duration-300 group-hover:text-white/65">{{ $service->short_description }}</p>
                    </a>
                @empty
                    <p class="col-span-full bg-white px-6 py-12 text-center text-slate-500">Nos services seront bientôt disponibles.</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Method. --}}
    <section class="section-space bg-white">
        <div class="container-site grid gap-12 lg:grid-cols-12 lg:gap-16">
            <div class="lg:col-span-4" data-reveal>
                <p class="eyebrow">Notre méthode</p>
                <h2 class="section-title mt-5">Simple dans la forme. Rigoureuse dans le fond.</h2>
                <p class="mt-6 max-w-md leading-7 text-slate-600">Une méthode lisible pour avancer sans zones d’ombre, de la première analyse au suivi de vos décisions.</p>
            </div>
            <div class="border-t border-line lg:col-span-8" data-stagger>
                @foreach([
                    ['01', 'search', 'Comprendre', 'Votre activité, vos priorités et les contraintes propres à votre organisation.'],
                    ['02', 'layers', 'Structurer', 'Les informations et les obligations pour construire une gestion plus claire.'],
                    ['03', 'handshake', 'Accompagner', 'Vos décisions dans la durée avec disponibilité, précision et pragmatisme.'],
                ] as [$number, $icon, $title, $description])
                    <div class="group grid gap-4 border-b border-line py-8 sm:grid-cols-[60px_56px_180px_1fr] sm:items-center sm:py-9" data-reveal>
                        <span class="font-heading text-xs font-bold tracking-[.18em] text-coral">{{ $number }}</span>
                        <span class="icon-badge icon-badge-fill !size-11"><x-line-icon :name="$icon" class="size-5" /></span>
                        <h3 class="font-heading text-xl font-bold text-charcoal">{{ $title }}</h3>
                        <p class="max-w-lg text-sm leading-7 text-slate-500">{{ $description }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Office location. --}}
    <section class="section-space bg-mist">
        <div class="container-site">
            <div class="grid gap-8 lg:grid-cols-12 lg:items-end">
                <div class="lg:col-span-7" data-reveal>
                    <p class="eyebrow">Notre bureau à {{ $city }}</p>
                    <h2 class="section-title mt-5 max-w-2xl">{{ $mapHeading }}</h2>
                </div>
            </div>

            <div class="relative mt-12 sm:mt-14" data-reveal>
                <div class="overflow-hidden rounded-2xl border border-line bg-white shadow-lift">
                    @if($embedUrl)
                        <iframe src="{{ $embedUrl }}" title="Localisation du bureau MCCG à {{ $city }}" class="block h-[420px] w-full border-0 grayscale-[.35] sm:h-[520px]" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                    @else
                        <div class="bg-zellige grid h-[420px] place-items-center text-center sm:h-[520px]"><div class="rounded-xl bg-white p-8 shadow-soft"><x-line-icon name="map-pin" class="mx-auto size-8 text-coral" /><p class="mt-4 font-heading text-xl font-bold">MCCG {{ $city }}</p><p class="mt-2 text-sm text-slate-500">{{ $region }}</p></div></div>
                    @endif
                </div>
                <div class="relative -mt-16 mx-4 rounded-2xl border border-line bg-white p-6 shadow-lift sm:mx-8 sm:p-8 lg:absolute lg:bottom-8 lg:left-8 lg:m-0 lg:w-[24rem]">
                    <p class="text-[11px] font-semibold uppercase tracking-[.2em] text-coral">MCCG {{ $city }}</p>
                    <ul class="mt-5 space-y-4 text-sm leading-6 text-slate-600">
                        <li class="flex gap-3"><x-line-icon name="map-pin" class="mt-0.5 size-[18px] shrink-0 text-coral" /><span class="font-medium text-charcoal">{!! $address !!}</span></li>
                        <li class="flex gap-3"><x-line-icon name="phone" class="mt-0.5 size-[18px] shrink-0 text-coral" /><a class="hover:text-coral" href="tel:{{ preg_replace('/\s+/', '', $phone) }}">{{ $phone }}</a></li>
                        <li class="flex gap-3"><x-line-icon name="mail" class="mt-0.5 size-[18px] shrink-0 text-coral" /><a class="break-all hover:text-coral" href="mailto:{{ $email }}">{{ $email }}</a></li>
                    </ul>
                    @if($mapsUrl)
                        <a href="{{ $mapsUrl }}" target="_blank" rel="noopener noreferrer" class="btn-primary mt-7 w-full">Ouvrir dans Google Maps <x-line-icon name="arrow-up-right" class="btn-arrow" stroke="2" /></a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Final call to action. --}}
    <section class="relative isolate overflow-hidden bg-charcoal py-20 text-white sm:py-24 lg:py-28">
        <div class="bg-zellige-light mask-fade-right absolute inset-y-0 right-0 -z-10 w-2/3 opacity-[.07]" aria-hidden="true"></div>
        <div class="absolute -right-24 -top-40 -z-10 size-96 glow glow-20" aria-hidden="true"></div>
        <div class="container-site grid gap-10 lg:grid-cols-12 lg:items-end">
            <div class="lg:col-span-8" data-reveal>
                <p class="eyebrow">MCCG {{ $city }}</p>
                <h2 class="mt-6 max-w-4xl font-heading text-3xl font-bold leading-[1.08] tracking-[-.03em] text-white sm:text-4xl lg:text-5xl">Parlons de votre entreprise et de la prochaine étape.</h2>
                <p class="mt-6 max-w-2xl leading-7 text-white/60">Notre équipe est à votre écoute pour comprendre vos besoins et construire un accompagnement adapté.</p>
            </div>
            <div class="lg:col-span-4 lg:flex lg:justify-end" data-reveal>
                <x-button-primary :href="route('contact')" class="w-full sm:w-auto">Prendre contact</x-button-primary>
            </div>
        </div>
    </section>

    {{-- Other offices. --}}
    <section class="section-space bg-white">
        <div class="container-site">
            <x-section-title eyebrow="Nos autres implantations" title="MCCG, au Maroc et à l’international" />
            <div class="mt-12"><x-office-cities :current="$route" /></div>
        </div>
    </section>
</div>
