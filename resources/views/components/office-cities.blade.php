@props(['current' => null])
@php
    $cities = [
        ['route' => 'marrakech', 'city' => 'Marrakech', 'label' => 'Bureau principal', 'country' => 'Maroc', 'image' => 'marrakech.webp', 'alt' => 'La Koutoubia à Marrakech'],
        ['route' => 'casablanca', 'city' => 'Casablanca', 'label' => 'Bureau', 'country' => 'Maroc', 'image' => 'casa.webp', 'alt' => 'Mosquée Hassan II à Casablanca'],
        ['route' => 'dubai', 'city' => 'Dubaï', 'label' => 'Bureau', 'country' => 'Émirats arabes unis', 'image' => 'dubai.webp', 'alt' => 'Vue panoramique de Dubaï'],
    ];
    if ($current) $cities = array_values(array_filter($cities, fn ($city) => $city['route'] !== $current));
@endphp
<div @class(['grid gap-5 sm:gap-6', 'md:grid-cols-3' => count($cities) === 3, 'md:grid-cols-2' => count($cities) === 2]) data-stagger>
    @foreach($cities as $city)
        <a href="{{ route($city['route']) }}" class="group relative block overflow-hidden rounded-2xl bg-charcoal" data-reveal>
            <img src="{{ asset('images/'.$city['image']) }}" alt="{{ $city['alt'] }}" @class(['img-zoom w-full object-cover opacity-90', 'aspect-[4/5] sm:aspect-[3/4] md:aspect-[4/5]' => count($cities) === 3, 'aspect-[4/3] md:aspect-[16/10]' => count($cities) !== 3]) width="640" height="800" loading="lazy">
            <span class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent" aria-hidden="true"></span>
            <span class="absolute left-5 top-5 rounded-full border border-white/25 bg-white/10 px-3 py-1 text-[11px] font-semibold uppercase tracking-wider text-white backdrop-blur-md">{{ $city['label'] }}</span>
            <span class="absolute inset-x-0 bottom-0 flex items-end justify-between gap-4 p-6 sm:p-7">
                <span>
                    <span class="block font-heading text-2xl font-bold text-white sm:text-3xl">{{ $city['city'] }}</span>
                    <span class="mt-1 block text-sm text-white/70">{{ $city['country'] }}</span>
                </span>
                <span class="grid size-12 shrink-0 place-items-center rounded-full bg-white text-charcoal transition duration-500 ease-premium group-hover:bg-coral group-hover:text-white"><x-line-icon name="arrow-up-right" class="size-5 transition-transform duration-500 ease-premium group-hover:rotate-45" stroke="2" /></span>
            </span>
        </a>
    @endforeach
</div>
