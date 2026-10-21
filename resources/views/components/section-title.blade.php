@props(['eyebrow' => null, 'title', 'text' => null, 'align' => 'left', 'dark' => false])
<div data-reveal @class(['max-w-3xl', 'mx-auto text-center' => $align === 'center'])>
    @if($eyebrow)<p class="eyebrow">{{ $eyebrow }}</p>@endif
    <h2 @class(['section-title mt-5', '!text-white' => $dark])>{{ $title }}</h2>
    @if($text)<p @class(['mt-5 text-base leading-7 sm:text-lg sm:leading-8', 'text-slate-600' => ! $dark, 'text-white/60' => $dark])>{{ $text }}</p>@endif
    <span class="section-title-line" aria-hidden="true"></span>
</div>
