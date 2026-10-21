@extends('layouts.app')
@section('content')
<x-page-hero eyebrow="Le cabinet" title="Une expertise engagée au service de votre entreprise" text="MCCG conjugue rigueur technique, compréhension du terrain marocain et proximité avec les dirigeants." />

<section class="section-space bg-white">
    <div class="container-site grid gap-14 lg:grid-cols-12 lg:items-center lg:gap-16">
        <div class="lg:col-span-6" data-reveal>
            <p class="eyebrow">Notre conviction</p>
            <h2 class="section-title mt-5">La confiance se construit dans la durée.</h2>
            <p class="mt-6 leading-8 text-slate-600">Nous avons créé MCCG autour d’une idée simple : l’expertise n’a de valeur que lorsqu’elle est comprise et utile. Nos consultants prennent le temps d’écouter, d’expliquer et de proposer des solutions qui correspondent réellement à votre activité.</p>
            <p class="mt-5 leading-8 text-slate-600">PME, groupes, entrepreneurs et investisseurs trouvent chez MCCG un partenaire exigeant, accessible et pleinement impliqué.</p>
            <div class="mt-10 grid grid-cols-2 gap-4">
                <div class="rounded-xl bg-charcoal p-6 text-white sm:p-7">
                    <strong class="font-heading text-3xl font-bold text-coral sm:text-4xl">360°</strong>
                    <p class="mt-3 text-sm leading-6 text-white/65">Une vision financière, fiscale, sociale et stratégique.</p>
                </div>
                <div class="card p-6 sm:p-7">
                    <strong class="break-words font-heading text-2xl font-bold text-charcoal sm:text-3xl">Sur mesure</strong>
                    <p class="mt-3 text-sm leading-6 text-slate-600">Des missions calibrées selon votre maturité et vos priorités.</p>
                </div>
            </div>
        </div>
        <div class="relative lg:col-span-6">
            <div class="overflow-hidden rounded-2xl" data-reveal-image>
                <img src="{{ asset('images/hero-mccg-960.webp') }}" alt="Échange entre consultants MCCG" class="aspect-[4/5] w-full object-cover sm:aspect-[5/4] lg:aspect-[4/5]" width="960" height="640" loading="lazy" data-parallax>
            </div>
            <div class="bg-zellige absolute -right-6 -top-6 -z-10 size-40 opacity-20" aria-hidden="true"></div>
            <figure class="relative -mt-20 mx-4 rounded-xl border-l-4 border-coral bg-white p-6 shadow-lift sm:mx-8 sm:p-8" data-reveal>
                <x-line-icon name="quote" class="size-7 text-coral" />
                <blockquote class="mt-4 font-heading text-lg font-semibold leading-8 text-charcoal sm:text-xl">« Être présents quand une décision compte, pas seulement quand une échéance approche. »</blockquote>
            </figure>
        </div>
    </div>
</section>

<section class="section-space relative overflow-hidden bg-mist">
    <div class="container-site">
        <x-section-title eyebrow="Nos principes" title="Une façon claire de travailler" />
        <div class="mt-12 grid gap-5 sm:mt-16 md:grid-cols-3 md:gap-6" data-stagger>
            @foreach([['01', 'ear', 'Écouter', 'Comprendre votre réalité avant de proposer une réponse.'], ['02', 'lightbulb', 'Éclairer', 'Rendre les sujets complexes lisibles et actionnables.'], ['03', 'flag', 'S’engager', 'Suivre les décisions et rester disponible dans la durée.']] as [$n, $icon, $t, $d])
                <div class="card card-interactive group relative overflow-hidden p-7 sm:p-9" data-reveal>
                    <span class="pointer-events-none absolute -right-2 -top-6 font-heading text-[7rem] font-bold leading-none text-mist transition-colors duration-300 group-hover:text-coral-50" aria-hidden="true">{{ $n }}</span>
                    <span class="icon-badge icon-badge-fill relative"><x-line-icon :name="$icon" class="size-5" /></span>
                    <h3 class="relative mt-8 font-heading text-xl font-bold text-charcoal">{{ $t }}</h3>
                    <p class="relative mt-3 leading-7 text-slate-600">{{ $d }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="section-space bg-white">
    <div class="container-site">
        <x-section-title eyebrow="Nos implantations" title="Un cabinet ancré au Maroc, ouvert à l’international" />
        <div class="mt-12 sm:mt-14"><x-office-cities /></div>
    </div>
</section>

<x-cta />
@endsection
