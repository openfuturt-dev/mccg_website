@extends('layouts.app')
@section('content')
<x-page-hero eyebrow="Nos services" title="Des expertises coordonnées pour avancer avec confiance" text="Tenue comptable, fiscalité, gestion sociale, audit interne et conseil : une équipe pluridisciplinaire, un accompagnement cohérent." />

<section class="section-space bg-mist">
    <div class="container-site">
        <div class="grid gap-5 md:grid-cols-2 md:gap-6 lg:grid-cols-3" data-stagger>
            @forelse($services as $service)
                <x-service-card :service="$service" :index="$loop->iteration" />
            @empty
                <p class="col-span-full text-center">Nos services seront bientôt disponibles.</p>
            @endforelse
        </div>
    </div>
</section>

<section class="section-space bg-white">
    <div class="container-site">
        <div class="grid gap-8 lg:grid-cols-12 lg:items-end">
            <div class="lg:col-span-7" data-reveal>
                <p class="eyebrow">Une méthode éprouvée</p>
                <h2 class="section-title mt-5">Le bon niveau d’accompagnement, au bon moment</h2>
            </div>
        </div>
        <div class="relative mt-14">
        <span class="absolute left-6 right-6 top-6 hidden h-px bg-gradient-to-r from-coral/60 via-line to-line md:block" aria-hidden="true"></span>
        <ol class="relative grid gap-10 md:grid-cols-3 md:gap-8" data-stagger>
            @foreach([['search', 'Diagnostic', 'Nous partons de vos enjeux, de vos outils et de votre organisation.'], ['route', 'Feuille de route', 'Nous définissons les priorités, les responsabilités et les résultats attendus.'], ['chart', 'Suivi', 'Nous mesurons les avancées et adaptons l’accompagnement à votre évolution.']] as [$icon, $t, $d])
                <li class="group relative" data-reveal>
                    <span class="icon-badge icon-badge-fill relative bg-white"><x-line-icon :name="$icon" class="size-5" /></span>
                    <p class="mt-7 font-heading text-xs font-bold tracking-[.18em] text-coral">ÉTAPE {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</p>
                    <h3 class="mt-2 font-heading text-xl font-bold text-charcoal">{{ $t }}</h3>
                    <p class="mt-3 max-w-sm leading-7 text-slate-600">{{ $d }}</p>
                </li>
            @endforeach
        </ol>
        </div>
    </div>
</section>

<x-cta />
@endsection
