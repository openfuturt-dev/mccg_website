@props(['service', 'index' => null])
@php
    $icon = match ($service->icon) {
        'calculator' => 'calculator',
        'scale' => 'scale',
        'check' => 'shield-check',
        'users' => 'users',
        'building' => 'building',
        default => 'trending-up',
    };
@endphp
<article class="card card-interactive group relative flex h-full flex-col overflow-hidden p-7 sm:p-8" data-reveal>
    <span class="absolute inset-x-0 top-0 h-0.5 origin-left scale-x-0 bg-coral transition-transform duration-500 ease-premium group-hover:scale-x-100" aria-hidden="true"></span>
    <div class="flex items-start justify-between">
        <span class="icon-badge icon-badge-fill"><x-line-icon :name="$icon" class="size-5" /></span>
        @if($index)<span class="font-heading text-sm font-semibold tracking-[.15em] text-slate-300">{{ str_pad($index, 2, '0', STR_PAD_LEFT) }}</span>@endif
    </div>
    <h3 class="mt-7 font-heading text-lg font-bold leading-snug text-charcoal sm:text-xl">{{ $service->title }}</h3>
    <p class="mt-3 grow text-[15px] leading-7 text-slate-600">{{ $service->short_description }}</p>
    <a href="{{ route('services.show', $service) }}" class="link-arrow mt-7 self-start after:absolute after:inset-0 after:content-['']">En savoir plus <x-line-icon name="arrow-right" class="btn-arrow" stroke="2" /></a>
</article>
