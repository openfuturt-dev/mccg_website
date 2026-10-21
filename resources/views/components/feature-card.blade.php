@props(['title', 'text', 'icon' => 'target', 'dark' => false])
<article @class([
    'group relative rounded-xl p-7 transition duration-300 ease-premium sm:p-8',
    'border border-white/10 bg-white/[.04] hover:border-coral/50 hover:bg-white/[.07]' => $dark,
    'card card-interactive' => ! $dark,
]) data-reveal>
    <span @class(['icon-badge icon-badge-fill', '!border-coral/40 !bg-transparent' => $dark])><x-line-icon :name="$icon" class="size-5" /></span>
    <h3 @class(['mt-7 font-heading text-lg font-bold', 'text-white' => $dark, 'text-charcoal' => ! $dark])>{{ $title }}</h3>
    <p @class(['mt-3 text-[15px] leading-7', 'text-white/60' => $dark, 'text-slate-600' => ! $dark])>{{ $text }}</p>
</article>
