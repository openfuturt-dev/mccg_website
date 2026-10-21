@props(['eyebrow' => 'Parlons de votre projet', 'title' => 'Besoin d’un accompagnement fiable pour votre entreprise ?'])
<section class="bg-white py-14 sm:py-20">
    <div class="container-site">
        <div class="relative isolate overflow-hidden rounded-3xl bg-charcoal px-6 py-12 text-white sm:px-12 sm:py-16 lg:px-16 lg:py-20" data-reveal>
            <div class="bg-zellige-light mask-fade-right absolute inset-y-0 right-0 -z-10 w-2/3 opacity-[.07]" aria-hidden="true"></div>
            <div class="absolute -right-24 -top-24 -z-10 size-80 rounded-full bg-coral/25 blur-3xl" aria-hidden="true"></div>
            <div class="grid gap-10 lg:grid-cols-12 lg:items-end">
                <div class="lg:col-span-8">
                    <p class="eyebrow">{{ $eyebrow }}</p>
                    <h2 class="mt-5 max-w-2xl font-heading text-3xl font-bold leading-[1.12] tracking-[-.02em] sm:text-4xl lg:text-[2.75rem]">{{ $title }}</h2>
                    <p class="mt-5 max-w-xl leading-7 text-white/60">Un premier échange pour comprendre votre situation et identifier la meilleure approche.</p>
                </div>
                <div class="flex flex-col gap-3 sm:flex-row lg:col-span-4 lg:flex-col lg:items-stretch">
                    <x-button-primary :href="route('contact', ['objet' => 'devis'])" class="w-full sm:w-auto">Demander un devis</x-button-primary>
                    <a href="{{ route('contact') }}" class="btn-outline-light w-full sm:w-auto">Nous contacter</a>
                </div>
            </div>
        </div>
    </div>
</section>
