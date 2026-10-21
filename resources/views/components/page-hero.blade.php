@props(['eyebrow', 'title', 'text'])
<section class="relative isolate overflow-hidden border-b border-line bg-white">
    <div class="bg-zellige mask-radial-tr absolute inset-y-0 right-0 -z-10 w-full opacity-[.06] sm:w-2/3" aria-hidden="true"></div>
    <div class="absolute -right-40 -top-40 -z-10 size-[28rem] glow glow-10" aria-hidden="true"></div>
    <div class="container-site py-14 sm:py-20 lg:py-24">
        <nav class="flex items-center gap-2 text-xs font-medium text-slate-400" aria-label="Fil d’Ariane" data-hero>
            <a href="{{ route('accueil') }}" class="hover:text-coral">Accueil</a>
            <x-line-icon name="chevron-right" class="size-3.5" />
            <span class="text-slate-600">{{ $eyebrow }}</span>
        </nav>
        <p class="eyebrow mt-8 sm:mt-10" data-hero>{{ $eyebrow }}</p>
        <h1 class="display-title mt-5 max-w-4xl break-words !text-[2rem] sm:!text-5xl lg:!text-[3.4rem]" data-split>{{ $title }}</h1>
        <p class="lead mt-6 max-w-2xl" data-hero>{{ $text }}</p>
        <span class="section-title-line is-inview" aria-hidden="true"></span>
    </div>
</section>
