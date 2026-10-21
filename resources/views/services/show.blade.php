@extends('layouts.app', ['seoTitle' => $service->meta_title ?: $service->title.' — MCCG', 'seoDescription' => $service->meta_description ?: $service->short_description])
@section('content')
<section class="relative isolate overflow-hidden border-b border-line bg-white">
    <div class="bg-zellige mask-radial-tr absolute inset-y-0 right-0 -z-10 w-full opacity-[.06] sm:w-2/3" aria-hidden="true"></div>
    <div class="absolute -right-40 -top-40 -z-10 size-[28rem] rounded-full bg-coral/10 blur-3xl" aria-hidden="true"></div>
    <div class="container-site py-14 sm:py-20 lg:py-24">
        <nav class="flex flex-wrap items-center gap-2 text-xs font-medium text-slate-400" aria-label="Fil d’Ariane" data-hero>
            <a href="{{ route('accueil') }}" class="hover:text-coral">Accueil</a>
            <x-line-icon name="chevron-right" class="size-3.5" />
            <a href="{{ route('services.index') }}" class="hover:text-coral">Services</a>
            <x-line-icon name="chevron-right" class="size-3.5" />
            <span class="min-w-0 break-words text-slate-600">{{ $service->title }}</span>
        </nav>
        <div class="mt-10 grid gap-10 lg:grid-cols-[1fr_320px] lg:items-end">
            <div>
                <p class="eyebrow" data-hero>Expertise MCCG</p>
                <h1 class="display-title mt-5 max-w-3xl break-words !text-[2rem] sm:!text-5xl lg:!text-[3.4rem]" data-split>{{ $service->title }}</h1>
                <p class="lead mt-6 max-w-2xl" data-hero>{{ $service->short_description }}</p>
            </div>
            <div data-hero><x-button-primary :href="route('contact', ['service' => $service->title])" class="w-full sm:w-auto lg:w-full">Demander un devis</x-button-primary></div>
        </div>
    </div>
</section>

<section class="section-space bg-white">
    <div class="container-site grid gap-12 lg:grid-cols-[minmax(0,1fr)_340px] lg:gap-16">
        <article class="prose-mccg" data-reveal>{!! $service->content !!}</article>
        <aside>
            <div class="relative overflow-hidden rounded-2xl bg-charcoal p-7 text-white shadow-lift sm:p-8 lg:sticky lg:top-28" data-reveal>
                <div class="bg-zellige-light mask-radial-tr absolute inset-0 opacity-[.08]" aria-hidden="true"></div>
                <div class="relative">
                    <span class="icon-badge !border-coral/40 !bg-transparent"><x-line-icon name="message" class="size-5" /></span>
                    <p class="mt-6 text-[11px] font-semibold uppercase tracking-[.2em] text-coral">Un besoin précis ?</p>
                    <h2 class="mt-3 font-heading text-xl font-bold">Échangez avec un consultant MCCG</h2>
                    <p class="mt-3 text-sm leading-6 text-white/65">Un premier échange pour comprendre votre situation et identifier la meilleure approche.</p>
                    <x-button-primary :href="route('contact', ['service' => $service->title])" class="mt-7 w-full">Nous contacter</x-button-primary>
                    <a href="tel:{{ preg_replace('/\s+/', '', config('mccg.phone')) }}" class="mt-4 flex items-center justify-center gap-2 text-sm text-white/70 hover:text-white"><x-line-icon name="phone" class="size-4 text-coral" />{{ config('mccg.phone') }}</a>
                </div>
            </div>
        </aside>
    </div>
</section>
<x-cta />
@endsection
