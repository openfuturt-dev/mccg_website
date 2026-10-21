@php
    $phone = config('mccg.phone');
    $email = config('mccg.email');
@endphp

<footer class="relative overflow-hidden bg-ink text-white/60">
    <div class="bg-zellige-light mask-radial-tr pointer-events-none absolute right-0 top-0 h-[28rem] w-[36rem] opacity-[.06]" aria-hidden="true"></div>
    <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-coral/70 to-transparent" aria-hidden="true"></div>

    <div class="container-site relative">
        <div class="grid gap-8 border-b border-white/10 py-14 sm:py-16 lg:grid-cols-12 lg:items-end">
            <div class="lg:col-span-7" data-reveal>
                <span class="inline-flex rounded-xl bg-white px-4 py-3"><img src="{{ asset('images/logo-mccg.png') }}" alt="MCCG" class="h-8 w-auto" width="233" height="56" loading="lazy"></span>
                <p class="mt-8 font-heading text-2xl font-bold leading-tight tracking-[-.02em] text-white sm:text-3xl">Expertise. Conseil. Engagement.</p>
                <p class="mt-2 font-heading text-lg text-white/50 sm:text-xl">Au service de votre <span class="text-coral">performance</span>.</p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row lg:col-span-5 lg:justify-end" data-reveal>
                <x-button-primary :href="route('contact', ['objet' => 'consultation'])" class="w-full sm:w-auto">Nous consulter</x-button-primary>
                <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}" class="btn-outline-light w-full sm:w-auto"><x-line-icon name="phone" class="size-4" />{{ $phone }}</a>
            </div>
        </div>

        <div class="grid gap-10 py-14 sm:grid-cols-2 lg:grid-cols-12 lg:gap-8" data-stagger>
            <div class="lg:col-span-4" data-reveal>
                <h2 class="mb-5 text-xs font-bold uppercase tracking-[.2em] text-white">Le cabinet</h2>
                <p class="max-w-sm text-sm leading-7">MCCG accompagne les entreprises et entrepreneurs au Maroc et à l’international en tenue comptable, fiscalité, audit interne, gestion sociale et conseil.</p>
                <div class="mt-6 flex gap-2">
                    <a class="grid size-11 place-items-center rounded-full border border-white/15 text-white/80 hover:border-coral hover:bg-coral hover:text-white" href="{{ config('mccg.linkedin_url') }}" target="_blank" rel="noopener noreferrer" aria-label="MCCG sur LinkedIn"><x-line-icon name="linkedin" class="size-4" /></a>
                    <a class="grid size-11 place-items-center rounded-full border border-white/15 text-white/80 hover:border-coral hover:bg-coral hover:text-white" href="{{ config('mccg.instagram_url') }}" target="_blank" rel="noopener noreferrer" aria-label="MCCG sur Instagram"><x-line-icon name="instagram" class="size-4" /></a>
                </div>
            </div>
            <div class="lg:col-span-2" data-reveal>
                <h2 class="mb-5 text-xs font-bold uppercase tracking-[.2em] text-white">Liens rapides</h2>
                <ul class="space-y-3 text-sm">
                    <li><a class="hover:text-coral" href="{{ route('a-propos') }}">À propos</a></li>
                    <li><a class="hover:text-coral" href="{{ route('services.index') }}">Nos services</a></li>
                    <li><a class="hover:text-coral" href="{{ route('articles.index') }}">Nos articles</a></li>
                    <li><a class="hover:text-coral" href="{{ route('contact') }}">Nous contacter</a></li>
                </ul>
            </div>
            <div class="lg:col-span-2" data-reveal>
                <h2 class="mb-5 text-xs font-bold uppercase tracking-[.2em] text-white">Nos sites</h2>
                <ul class="space-y-3 text-sm">
                    <li><a class="hover:text-coral" href="{{ route('marrakech') }}">Marrakech</a></li>
                    <li><a class="hover:text-coral" href="{{ route('casablanca') }}">Casablanca</a></li>
                    <li><a class="hover:text-coral" href="{{ route('dubai') }}">Dubaï</a></li>
                </ul>
            </div>
            <div class="lg:col-span-4" data-reveal>
                <h2 class="mb-5 text-xs font-bold uppercase tracking-[.2em] text-white">Contact</h2>
                <ul class="space-y-4 text-sm leading-6">
                    <li class="flex gap-3"><x-line-icon name="phone" class="mt-0.5 size-4 shrink-0 text-coral" /><a class="hover:text-coral" href="tel:{{ preg_replace('/\s+/', '', $phone) }}">{{ $phone }}</a></li>
                    <li class="flex gap-3"><x-line-icon name="mail" class="mt-0.5 size-4 shrink-0 text-coral" /><a class="break-all hover:text-coral" href="mailto:{{ $email }}">{{ $email }}</a></li>
                    <li class="flex gap-3"><x-line-icon name="map-pin" class="mt-0.5 size-4 shrink-0 text-coral" /><span class="break-words">{{ config('mccg.address') }}</span></li>
                    <li class="flex gap-3"><x-line-icon name="building" class="mt-0.5 size-4 shrink-0 text-coral" /><span><span class="text-white">Autres bureaux :</span> {{ implode(', ', config('mccg.other_offices', [])) }}</span></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="relative border-t border-white/10"><p class="container-site py-5 text-xs leading-6 text-white/45">MCCG propose des services d’accompagnement comptable, fiscal, social et administratif. Les prestations réglementées sont réalisées uniquement lorsqu’elles sont légalement autorisées ou en collaboration avec des professionnels habilités.</p></div>
    <div class="relative border-t border-white/10">
        <div class="container-site flex flex-col gap-3 py-5 text-xs sm:flex-row sm:items-center sm:justify-between sm:py-6">
            <p>© {{ date('Y') }} MCCG. Tous droits réservés.</p>
            <div class="flex flex-wrap gap-x-6 gap-y-2"><a class="inline-flex min-h-11 items-center hover:text-coral sm:min-h-0" href="{{ route('confidentialite') }}">Confidentialité</a><a class="inline-flex min-h-11 items-center hover:text-coral sm:min-h-0" href="{{ route('conditions') }}">Conditions</a></div>
        </div>
    </div>
</footer>
