@props(['article'])
<article class="card card-interactive group relative flex h-full flex-col overflow-hidden" data-reveal>
    <div class="relative aspect-[16/10] overflow-hidden bg-mist">
        @if($article->featured_image)
            <img src="{{ asset('storage/'.$article->featured_image) }}" alt="{{ $article->title }}" class="img-zoom h-full w-full object-cover" loading="lazy">
        @else
            <div class="bg-zellige grid h-full place-items-center bg-mist [background-size:48px]"><div class="grid h-full w-full place-items-center bg-mist/80"><img src="{{ asset('images/logo-mccg.png') }}" alt="" class="img-zoom w-36 opacity-50" width="233" height="56" loading="lazy"></div></div>
        @endif
        <span class="absolute left-4 top-4 rounded-full bg-white/95 px-3 py-1 text-[11px] font-semibold uppercase tracking-wider text-coral shadow-soft">{{ $article->category?->name ?? 'Conseil' }}</span>
    </div>
    <div class="flex grow flex-col p-6 sm:p-7">
        <time class="text-xs font-medium uppercase tracking-wider text-slate-400">{{ $article->published_at?->translatedFormat('d M Y') }}</time>
        <h3 class="mt-3 font-heading text-lg font-bold leading-snug text-charcoal transition-colors duration-300 group-hover:text-coral"><a href="{{ route('articles.show', $article) }}" class="after:absolute after:inset-0 after:content-['']">{{ $article->title }}</a></h3>
        <p class="mt-3 line-clamp-3 grow text-[15px] leading-7 text-slate-600">{{ $article->excerpt }}</p>
        <span class="link-arrow mt-6 self-start">Lire l’article <x-line-icon name="arrow-right" class="btn-arrow" stroke="2" /></span>
    </div>
</article>
