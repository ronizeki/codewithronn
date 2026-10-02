@props(['path', 'alt', 'width', 'height', 'sizes' => '100vw', 'eager' => false])
@php
    $smallPath = preg_replace('/\.webp$/', '-sm.webp', $path);
    $hasSmall = $smallPath !== $path && is_file(public_path($smallPath));
    $smallWidth = $hasSmall ? getimagesize(public_path($smallPath))[0] : null;
@endphp
<img src="{{ asset($path) }}" @if($hasSmall) srcset="{{ asset($smallPath) }} {{ $smallWidth }}w, {{ asset($path) }} {{ $width }}w" sizes="{{ $sizes }}" @endif alt="{{ $alt }}" width="{{ $width }}" height="{{ $height }}" @if($eager) fetchpriority="high" @else loading="lazy" decoding="async" @endif {{ $attributes }}>
