@extends('layouts.app', ['seoTitle' => $article->meta_title ?: $article->title.' — MCCG', 'seoDescription' => $article->meta_description ?: $article->excerpt, 'seoImage' => $article->featured_image ? asset('storage/'.$article->featured_image) : null, 'seoKeywords' => $article->keywords, 'ogType' => 'article'])
@push('structured-data')
<script type="application/ld+json">{!! json_encode(['@'.'context'=>'https://schema.org','@type'=>'Article','headline'=>$article->title,'description'=>$article->excerpt,'datePublished'=>$article->published_at?->toIso8601String(),'dateModified'=>$article->updated_at->toIso8601String(),'author'=>['@type'=>'Organization','name'=>'MCCG'],'publisher'=>['@type'=>'Organization','name'=>'MCCG']], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}</script>
@endpush
@section('content')
<article>
    <header class="relative isolate overflow-hidden bg-white pb-12 pt-14 sm:pb-16 sm:pt-20">
        <div class="bg-zellige mask-radial-tr absolute inset-y-0 right-0 -z-10 w-2/3 opacity-[.06]" aria-hidden="true"></div>
        <div class="container-site max-w-4xl">
            <nav class="flex flex-wrap items-center gap-2 text-xs font-medium text-slate-400" aria-label="Fil d’Ariane" data-hero>
                <a href="{{ route('accueil') }}" class="hover:text-coral">Accueil</a>
                <x-line-icon name="chevron-right" class="size-3.5" />
                <a href="{{ route('articles.index') }}" class="hover:text-coral">Articles</a>
            </nav>
            <div class="mt-8 flex flex-wrap items-center gap-3 text-xs font-medium uppercase tracking-wider text-slate-400" data-hero>
                @if($article->category)<span class="rounded-full bg-coral-50 px-3 py-1 font-semibold text-coral">{{ $article->category->name }}</span>@endif
                <time>{{ $article->published_at?->translatedFormat('d F Y') }}</time>
            </div>
            <h1 class="display-title mt-6 break-words !text-[2rem] sm:!text-5xl" data-split>{{ $article->title }}</h1>
            <p class="lead mt-6 max-w-3xl" data-hero>{{ $article->excerpt }}</p>
        </div>
    </header>
    @if($article->featured_image)
        <div class="container-site max-w-5xl">
            <div class="overflow-hidden rounded-2xl" data-reveal-image data-hero-image>
                <img src="{{ asset('storage/'.$article->featured_image) }}" alt="{{ $article->title }}" class="aspect-[16/9] w-full object-cover sm:aspect-[16/8]">
            </div>
        </div>
    @endif
    <div class="container-site grid max-w-5xl gap-10 py-14 sm:gap-14 sm:py-20 lg:grid-cols-[minmax(0,1fr)_200px]">
        <div class="prose-mccg min-w-0">{!! $article->content !!}</div>
        <aside class="order-first lg:order-last">
            <div class="card p-5 text-sm lg:sticky lg:top-28">
                <p class="text-[11px] font-semibold uppercase tracking-[.2em] text-slate-400">Publié par</p>
                <p class="mt-2 font-heading font-bold text-charcoal">{{ $article->author?->name ?? 'Équipe MCCG' }}</p>
                <a href="{{ route('contact') }}" class="link-arrow group mt-5">Une question ? <x-line-icon name="arrow-right" class="btn-arrow" stroke="2" /></a>
            </div>
        </aside>
    </div>
</article>
@if($related->isNotEmpty())
<section class="section-space border-t border-line bg-mist">
    <div class="container-site">
        <x-section-title eyebrow="Nos publications" title="À lire également" />
        <div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3" data-stagger>@foreach($related as $item)<x-article-card :article="$item" />@endforeach</div>
    </div>
</section>
@endif
@endsection
