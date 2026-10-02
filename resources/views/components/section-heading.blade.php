@props(['eyebrow', 'title', 'text' => null])
<div {{ $attributes->class(['section-heading']) }}><p class="eyebrow">{{ $eyebrow }}</p><h2>{{ $title }}</h2>@if($text)<p class="section-intro">{{ $text }}</p>@endif</div>
