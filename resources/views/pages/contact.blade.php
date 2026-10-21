@extends('layouts.app')

@php
    $phone = config('mccg.phone');
    $email = config('mccg.email');
    $address = config('mccg.address');
    $otherOffices = config('mccg.other_offices', []);
    $officeLocations = config('mccg.office_locations', []);
    $iconClass = 'size-5 stroke-current';
@endphp

@section('content')
<x-page-hero eyebrow="Contact" title="Commençons par une conversation" text="Décrivez-nous votre besoin. Un membre de notre équipe vous répondra avec clarté, sans jargon inutile." />

<section class="section-space bg-mist">
    <div class="container-site grid gap-12 lg:grid-cols-[.8fr_1.2fr] lg:items-start lg:gap-14">
        <div data-reveal>
            <p class="eyebrow">Nous joindre</p>
            <h2 class="section-title mt-5">À votre écoute</h2>
            <p class="mt-5 leading-8 text-slate-600">Qu’il s’agisse d’une question ponctuelle, d’un projet de création ou d’un accompagnement dans la durée, nous prendrons le temps de comprendre votre contexte.</p>

            <div class="mt-9 grid gap-3 sm:grid-cols-2 lg:grid-cols-1">
                <a class="card group flex items-center gap-4 p-5 transition duration-500 ease-premium hover:border-coral/40 hover:shadow-lift" href="tel:{{ preg_replace('/\s+/', '', $phone) }}">
                    <span class="icon-badge icon-badge-fill !size-11"><x-line-icon name="phone" class="size-5" /></span>
                    <span class="grow"><span class="block text-[11px] font-semibold uppercase tracking-[.18em] text-slate-400">Téléphone</span><span class="mt-1 block font-semibold text-charcoal group-hover:text-coral">{{ $phone }}</span></span>
                    <x-line-icon name="arrow-up-right" class="size-4 text-slate-300 transition group-hover:text-coral" />
                </a>
                <a class="card group flex min-w-0 items-center gap-4 p-5 transition duration-500 ease-premium hover:border-coral/40 hover:shadow-lift" href="mailto:{{ $email }}">
                    <span class="icon-badge icon-badge-fill !size-11"><x-line-icon name="mail" class="size-5" /></span>
                    <span class="min-w-0 grow"><span class="block text-[11px] font-semibold uppercase tracking-[.18em] text-slate-400">E-mail</span><span class="mt-1 block break-all font-semibold text-charcoal group-hover:text-coral">{{ $email }}</span></span>
                    <x-line-icon name="arrow-up-right" class="size-4 shrink-0 text-slate-300 transition group-hover:text-coral" />
                </a>
                <div class="card flex gap-4 p-5 sm:col-span-2 lg:col-span-1">
                    <span class="icon-badge !size-11"><x-line-icon name="map-pin" class="size-5" /></span>
                    <span><span class="block text-[11px] font-semibold uppercase tracking-[.18em] text-slate-400">Adresse</span><span class="mt-1 block font-semibold leading-6 text-charcoal">{{ $address }}</span></span>
                </div>
                <div class="card flex gap-4 p-5">
                    <span class="icon-badge !size-11"><x-line-icon name="building" class="size-5" /></span>
                    <span><span class="block text-[11px] font-semibold uppercase tracking-[.18em] text-slate-400">Autres bureaux</span><span class="mt-1 block font-semibold text-charcoal">{{ implode(', ', $otherOffices) }}</span></span>
                </div>
                <div class="card flex gap-4 p-5">
                    <span class="icon-badge !size-11"><x-line-icon name="clock" class="size-5" /></span>
                    <span><span class="block text-[11px] font-semibold uppercase tracking-[.18em] text-slate-400">Horaires</span><span class="mt-1 block font-semibold text-charcoal">{{ config('mccg.hours') }}</span></span>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-2 gap-3 sm:flex sm:flex-wrap">
                <a class="btn-secondary !px-4" href="{{ config('mccg.linkedin_url') }}" target="_blank" rel="noopener noreferrer" aria-label="MCCG sur LinkedIn"><x-line-icon name="linkedin" class="size-4" />LinkedIn</a>
                <a class="btn-secondary !px-4" href="{{ config('mccg.instagram_url') }}" target="_blank" rel="noopener noreferrer" aria-label="MCCG sur Instagram"><x-line-icon name="instagram" class="size-4" />Instagram</a>
            </div>
        </div>

        <x-contact-form :services="$services" />
    </div>
</section>

<section class="bg-white py-14 sm:py-20 lg:py-24">
    <div class="container-site" data-office-carousel>
        <div class="mb-8 flex flex-col justify-between gap-6 sm:mb-9 md:flex-row md:items-end">
            <div>
                <p class="eyebrow">Implantations</p>
                <h2 class="section-title mt-5">Nos bureaux</h2>
                <p class="mt-5 max-w-2xl leading-8 text-slate-600">Retrouvez notre bureau principal à Marrakech. MCCG accompagne également ses clients à Casablanca et Dubai.</p>
            </div>
            <div class="flex items-center gap-2 self-end md:self-auto" aria-label="Navigation entre les bureaux">
                <button class="grid size-11 place-items-center rounded-full border border-slate-200 bg-white text-charcoal hover:border-coral hover:text-coral disabled:cursor-not-allowed disabled:opacity-40" type="button" data-office-previous aria-label="Bureau précédent"><x-line-icon name="arrow-left" class="size-4" stroke="2" /></button>
                <button class="grid size-11 place-items-center rounded-full border border-slate-200 bg-white text-charcoal hover:border-coral hover:text-coral disabled:cursor-not-allowed disabled:opacity-40" type="button" data-office-next aria-label="Bureau suivant"><x-line-icon name="arrow-right" class="size-4" stroke="2" /></button>
            </div>
        </div>

        <div class="mb-5 grid grid-cols-3 gap-2 sm:flex sm:flex-wrap" role="tablist" aria-label="Choisir une ville">
            @foreach($officeLocations as $index => $office)
                <button class="min-h-11 rounded-full border px-2 py-2.5 text-xs font-semibold transition sm:px-5 sm:text-sm {{ $index === 0 ? 'border-coral bg-coral text-white' : 'border-slate-200 bg-white text-charcoal hover:border-coral hover:text-coral' }}" type="button" role="tab" aria-selected="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="office-panel-{{ $index }}" data-office-tab="{{ $index }}">{{ $office['city'] }}</button>
            @endforeach
        </div>

        <div class="overflow-hidden rounded-2xl border border-line bg-white shadow-lift">
            <div class="flex transition-transform duration-500 ease-out" data-office-track>
                @foreach($officeLocations as $index => $office)
                    <article class="grid min-w-full lg:grid-cols-[.7fr_1.3fr]" id="office-panel-{{ $index }}" role="tabpanel" aria-label="Bureau MCCG {{ $office['city'] }}" data-office-panel>
                        <div class="flex flex-col justify-center p-6 sm:p-10 lg:p-12">
                            <span class="icon-badge"><x-line-icon name="map-pin" class="size-5" /></span>
                            <p class="mt-6 text-xs font-semibold uppercase tracking-[.2em] text-coral">Bureau MCCG</p>
                            <h3 class="mt-2 font-heading text-3xl font-bold text-charcoal">{{ $office['city'] }}</h3>
                            <p class="mt-5 border-l-2 border-coral pl-4 font-semibold leading-7 text-charcoal">{{ $office['address'] }}</p>
                            <div class="mt-8">
                                @if($office['maps_url'])
                                    <a class="btn-primary w-full sm:w-auto" href="{{ $office['maps_url'] }}" target="_blank" rel="noopener noreferrer">Ouvrir dans Google Maps <x-line-icon name="arrow-up-right" class="btn-arrow" stroke="2" /></a>
                                @else
                                    <span class="btn-primary cursor-not-allowed opacity-60" aria-disabled="true">Ouvrir dans Google Maps</span>
                                @endif
                            </div>
                        </div>
                        <div class="min-h-72 bg-mist sm:min-h-80 lg:min-h-[460px]">
                            @if($office['embed_url'])
                                <iframe class="h-full min-h-72 w-full sm:min-h-80 lg:min-h-[460px]" src="{{ $office['embed_url'] }}" title="Carte du bureau MCCG à {{ $office['city'] }}" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                            @else
                                <div class="grid h-full min-h-80 place-items-center p-8 text-center lg:min-h-[460px]" data-map-fallback="{{ $office['city'] }}"><div><span class="mx-auto grid size-16 place-items-center rounded-full bg-white text-coral shadow-sm">⌖</span><p class="mt-5 font-heading text-xl font-bold text-charcoal">Bureau {{ $office['city'] }}</p><p class="mt-3 text-slate-600">{{ $office['address'] }}</p></div></div>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
        <p class="mt-4 text-center text-xs text-slate-400" aria-live="polite" data-office-status>1 / {{ count($officeLocations) }} · Marrakech</p>
    </div>
</section>
@endsection
