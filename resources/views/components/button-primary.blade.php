@props(['href' => null, 'type' => 'button'])
@if($href)
<a href="{{ $href }}" {{ $attributes->class('btn-primary') }}>{{ $slot }}<x-line-icon name="arrow-right" class="btn-arrow" stroke="2" /></a>
@else
<button type="{{ $type }}" {{ $attributes->class('btn-primary') }}>{{ $slot }}<x-line-icon name="arrow-right" class="btn-arrow" stroke="2" /></button>
@endif
