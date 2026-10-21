@extends('layouts.app')
@section('content')

{{-- Hero: layout from section 6 of the brand guidelines. --}}
<section class="relative isolate overflow-hidden bg-white">
    <div class="absolute inset-y-0 right-0 -z-10 hidden w-[54%] lg:block" data-reveal-image data-hero-image>
        <img src="{{ asset('images/hero-mccg-1600.webp') }}" srcset="{{ asset('images/hero-mccg-960.webp') }} 960w, {{ asset('images/hero-mccg-1600.webp') }} 1600w" sizes="54vw" alt="Consultants MCCG analysant des documents financiers" class="h-full w-full object-cover object-[60%_center]" width="1600" height="1067" fetchpriority="high" data-parallax>
        <div class="absolute inset-y-0 left-0 w-2/5 bg-gradient-to-r from-white via-white/70 to-transparent"></div>
        <div class="absolute inset-x-0 bottom-0 h-40 bg-gradient-to-t from-white/60 to-transparent"></div>
    </div>
    <div class="bg-zellige mask-radial absolute -left-40 -top-40 -z-10 size-[34rem] opacity-[.07]" aria-hidden="true"></div>

    <div class="container-site pb-10 pt-14 sm:pt-20 lg:flex lg:min-h-[680px] lg:items-center lg:pb-44 lg:pt-16">
        <div class="max-w-[38rem]">
            <p class="eyebrow" data-hero>Cabinet de conseil comptable et fiscal au Maroc</p>
            <h1 class="display-title mt-6 break-words" data-split>Votre partenaire de confiance en <span class="highlight-underline text-coral">conseil comptable, fiscal et social</span></h1>
            <p class="lead mt-7 max-w-xl" data-hero>MCCG accompagne les entreprises, entrepreneurs et dirigeants au Maroc dans la tenue comptable, la fiscalité, la gestion sociale, le conseil et le pilotage administratif.</p>
            <div class="mt-9 flex flex-col gap-3 sm:flex-row" data-hero>
                <x-button-primary :href="route('services.index')" class="w-full sm:w-auto">Découvrir nos services</x-button-primary>
                <x-button-secondary :href="route('contact')" class="w-full sm:w-auto">Nous contacter</x-button-secondary>
            </div>
            <ul class="mt-10 flex flex-wrap gap-x-7 gap-y-3 text-sm font-medium text-slate-600" data-hero>
                @foreach(['Conseil personnalisé', 'Expertise pluridisciplinaire', 'Accompagnement durable'] as $point)
                    <li class="flex items-center gap-2"><span class="grid size-5 place-items-center rounded-full bg-coral-50 text-coral"><x-line-icon name="check" class="size-3" stroke="2.5" /></span>{{ $point }}</li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- Mobile / tablet image. --}}
    <div class="container-site pb-8 lg:hidden">
        <div class="relative overflow-hidden rounded-2xl shadow-lift" data-reveal-image>
            <img src="{{ asset('images/hero-mccg-960.webp') }}" alt="" class="aspect-[4/3] w-full object-cover" width="960" height="640" loading="lazy">
        </div>
    </div>
</section>

{{-- Pillars card overlapping the hero. --}}
<section class="relative z-10 -mt-16 pb-4 lg:-mt-24">
    <div class="container-site">
        <div class="grid overflow-hidden rounded-2xl border border-line bg-white shadow-lift md:grid-cols-3" data-stagger>
            @foreach([
                ['target', 'Expertise', 'Une équipe pluridisciplinaire pour des solutions précises et adaptées.'],
                ['users', 'Proximité', 'Une relation de confiance basée sur l’écoute et la réactivité.'],
                ['trending-up', 'Performance', 'Des conseils concrets pour optimiser vos résultats et sécuriser votre avenir.'],
            ] as [$icon, $title, $text])
                <div class="group flex gap-5 border-b border-line p-7 last:border-b-0 sm:p-8 md:flex-col md:items-center md:border-b-0 md:border-r md:text-center md:last:border-r-0 lg:p-10" data-reveal>
                    <span class="icon-badge-soft transition duration-300 ease-premium group-hover:-translate-y-1 group-hover:shadow-lift"><x-line-icon :name="$icon" class="size-7" stroke="1.4" /></span>
                    <div>
                        <h2 class="font-heading text-lg font-bold text-charcoal md:mt-2">{{ $title }}</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-600">{{ $text }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Services. --}}
<section class="section-space bg-white">
    <div class="container-site">
        <div class="flex flex-col justify-between gap-8 lg:flex-row lg:items-end">
            <x-section-title eyebrow="Nos expertises" title="Des solutions concrètes pour chaque étape de votre entreprise" text="Une approche pluridisciplinaire qui rassemble tenue comptable, fiscalité, gestion sociale, accompagnement administratif et conseil aux entreprises." />
            <x-button-secondary :href="route('services.index')" class="w-full shrink-0 sm:w-auto" data-reveal>Voir tous nos services</x-button-secondary>
        </div>
        <div class="mt-12 grid gap-5 md:mt-16 md:grid-cols-2 md:gap-6 lg:grid-cols-3" data-stagger>
            @forelse($services as $service)
                <x-service-card :service="$service" :index="$loop->iteration" />
            @empty
                <p class="col-span-full text-center">Aucun service publié pour le moment.</p>
            @endforelse
        </div>
    </div>
</section>

{{-- About. --}}
<section class="section-space relative overflow-hidden bg-mist">
    <div class="container-site grid gap-14 lg:grid-cols-12 lg:items-center lg:gap-16">
        <div class="relative lg:col-span-6">
            <div class="relative overflow-hidden rounded-2xl" data-reveal-image>
                <img src="{{ asset('images/marrakech-800.webp') }}" srcset="{{ asset('images/marrakech-800.webp') }} 800w, {{ asset('images/marrakech.webp') }} 1260w" sizes="(min-width: 1024px) 600px, 100vw" alt="Marrakech, siège du cabinet MCCG" class="aspect-[5/4] w-full object-cover" width="1260" height="840" loading="lazy" decoding="async" data-parallax>
            </div>
            <div class="bg-zellige absolute -bottom-8 -left-8 -z-10 size-48 opacity-20" aria-hidden="true"></div>
            <div class="absolute -bottom-8 right-4 max-w-[17rem] rounded-xl bg-charcoal p-6 text-white shadow-lift sm:right-8" data-reveal>
                <x-line-icon name="quote" class="size-6 text-coral" />
                <p class="mt-3 font-heading text-sm font-medium leading-6">Être présents quand une décision compte, pas seulement quand une échéance approche.</p>
            </div>
        </div>
        <div class="pt-6 lg:col-span-6 lg:pt-0">
            <div data-reveal>
                <p class="eyebrow">À propos de MCCG</p>
                <h2 class="section-title mt-5">L’expertise technique, avec une vraie proximité</h2>
                <p class="mt-6 leading-8 text-slate-600">MCCG accompagne les entreprises, entrepreneurs et investisseurs avec une lecture claire de leurs obligations et de leurs leviers de développement. Notre rôle va au-delà de la conformité : nous vous aidons à décider avec confiance.</p>
                <p class="mt-5 leading-8 text-slate-600">Notre connaissance du contexte marocain et notre approche pluridisciplinaire permettent de construire des réponses cohérentes, pragmatiques et durables.</p>
            </div>
            <dl class="mt-10 grid grid-cols-3 divide-x divide-line border-y border-line" data-reveal>
                <div class="py-6 pr-4"><dt class="sr-only">Domaines d’expertise</dt><dd class="font-heading text-3xl font-bold text-charcoal sm:text-4xl" data-counter="{{ $services->count() }}">{{ $services->count() }}</dd><dd class="mt-1 text-xs leading-5 text-slate-500 sm:text-sm">Domaines d’expertise</dd></div>
                <div class="px-4 py-6"><dt class="sr-only">Implantations</dt><dd class="font-heading text-3xl font-bold text-charcoal sm:text-4xl" data-counter="3">3</dd><dd class="mt-1 text-xs leading-5 text-slate-500 sm:text-sm">Bureaux · Maroc &amp; Dubaï</dd></div>
                <div class="py-6 pl-4"><dt class="sr-only">Vision</dt><dd class="font-heading text-3xl font-bold text-coral sm:text-4xl" data-counter="360" data-suffix="°">360°</dd><dd class="mt-1 text-xs leading-5 text-slate-500 sm:text-sm">Vision de votre entreprise</dd></div>
            </dl>
            <ul class="mt-8 grid gap-4 sm:grid-cols-2" data-stagger>
                @foreach([['target', 'Expertise', 'Des compétences rigoureuses et continuellement actualisées.'], ['clipboard-list', 'Rigueur', 'Des méthodes fiables et une attention constante aux détails.'], ['users', 'Proximité', 'Un interlocuteur disponible qui comprend votre activité.'], ['handshake', 'Engagement', 'Des recommandations suivies dans la durée.']] as [$icon, $title, $text])
                    <li class="flex gap-4" data-reveal><span class="icon-badge !size-10"><x-line-icon :name="$icon" class="size-[18px]" /></span><span><span class="block font-heading font-bold text-charcoal">{{ $title }}</span><span class="mt-1 block text-sm leading-6 text-slate-600">{{ $text }}</span></span></li>
                @endforeach
            </ul>
            <div class="mt-10" data-reveal><x-button-primary :href="route('a-propos')" class="w-full sm:w-auto">Découvrir le cabinet</x-button-primary></div>
        </div>
    </div>
</section>

{{-- Why MCCG (dark band). --}}
<section class="section-space relative isolate overflow-hidden bg-charcoal">
    <div class="bg-zellige-light mask-radial absolute left-1/2 top-1/2 -z-10 size-[60rem] -translate-x-1/2 -translate-y-1/2 opacity-[.05]" aria-hidden="true"></div>
    <div class="absolute -bottom-40 -left-40 -z-10 size-96 glow glow-20" aria-hidden="true"></div>
    <div class="container-site">
        <x-section-title eyebrow="Pourquoi MCCG" title="Un partenaire structuré autour de votre performance" text="Notre manière de travailler privilégie la clarté, la responsabilité et l’utilité concrète de chaque recommandation." align="center" dark />
        <div class="mt-14 grid gap-5 sm:mt-16 md:grid-cols-2 md:gap-6 lg:grid-cols-4" data-stagger>
            <x-feature-card dark icon="briefcase" title="Expertise" text="Une équipe pluridisciplinaire au fait des exigences marocaines." />
            <x-feature-card dark icon="shield-check" title="Confiance" text="Une relation transparente et une information financière fiable." />
            <x-feature-card dark icon="handshake" title="Accompagnement" text="Une présence attentive, de vos opérations courantes à vos projets." />
            <x-feature-card dark icon="chart" title="Performance" text="Des analyses qui transforment vos chiffres en décisions utiles." />
        </div>
    </div>
</section>

{{-- Offices. --}}
<section class="section-space bg-white">
    <div class="container-site">
        <div class="flex flex-col justify-between gap-8 lg:flex-row lg:items-end">
            <x-section-title eyebrow="Nos implantations" title="Proches de vous, au Maroc et à l’international" text="Un bureau principal à Marrakech, une présence à Casablanca et à Dubaï pour accompagner vos projets là où ils se développent." />
            <x-button-secondary :href="route('contact')" class="w-full shrink-0 sm:w-auto" data-reveal>Trouver un bureau</x-button-secondary>
        </div>
        <div class="mt-12 md:mt-16"><x-office-cities /></div>
    </div>
</section>

@if($articles->isNotEmpty())
<section class="section-space border-t border-line bg-mist">
    <div class="container-site">
        <div class="flex flex-col justify-between gap-8 md:flex-row md:items-end">
            <x-section-title eyebrow="Nos publications" title="Éclairages & actualités" text="Des repères utiles pour mieux comprendre les enjeux comptables, fiscaux et réglementaires de votre entreprise." />
            <x-button-secondary :href="route('articles.index')" class="w-full shrink-0 md:w-auto" data-reveal>Tous les articles</x-button-secondary>
        </div>
        <div class="mt-12 grid gap-6 md:mt-14 md:grid-cols-2 lg:grid-cols-3" data-stagger>
            @foreach($articles as $article)<x-article-card :article="$article" />@endforeach
        </div>
    </div>
</section>
@endif

<x-cta />
@endsection
