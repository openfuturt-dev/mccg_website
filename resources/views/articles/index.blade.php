@extends('layouts.app')
@section('content')
<x-page-hero eyebrow="Éclairages" title="Comprendre aujourd’hui pour mieux décider demain" text="Analyses, repères pratiques et actualités pour les dirigeants et responsables financiers au Maroc." />
<section class="section-space bg-mist">
    <div class="container-site">
        <div class="mb-10 flex flex-wrap gap-2 sm:mb-12" data-reveal>
            <a href="{{ route('articles.index') }}" class="filter-chip {{ !request('categorie') ? 'active' : '' }}">Tous</a>
            @foreach($categories as $category)
                <a href="{{ route('articles.index', ['categorie' => $category->slug]) }}" class="filter-chip {{ request('categorie') === $category->slug ? 'active' : '' }}">{{ $category->name }}</a>
            @endforeach
        </div>
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3" data-stagger>
            @forelse($articles as $article)
                <x-article-card :article="$article" />
            @empty
                <div class="card col-span-full p-10 text-center sm:p-14">
                    <span class="icon-badge mx-auto"><x-line-icon name="file-spreadsheet" class="size-5" /></span>
                    <h2 class="mt-6 font-heading text-xl font-bold text-charcoal">Aucun article dans cette rubrique</h2>
                    <p class="mt-2 text-slate-600">Revenez bientôt pour découvrir nos prochains éclairages.</p>
                </div>
            @endforelse
        </div>
        <div class="mt-12 overflow-x-auto">{{ $articles->links() }}</div>
    </div>
</section>
@endsection
