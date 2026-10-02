@props(['name' => 'arrow', 'size' => 22])
@php
    $paths = [
        'arrow' => 'M5 12h14M12 5l7 7-7 7',
        'external' => 'M7 17 17 7M7 7h10v10',
        'window' => 'M3 8h18M6 5h.01M9 5h.01M3 3h18v18H3z',
        'code' => 'm8 7-5 5 5 5m8-10 5 5-5 5m-3-13-2 16',
        'bolt' => 'm13 2-9 12h7l-1 8 10-12h-7z',
        'layers' => 'm12 3 10 5-10 5L2 8zm-10 9 10 5 10-5M2 16l10 5 10-5',
        'grid' => 'M3 3h7v7H3zm11 0h7v7h-7zM3 14h7v7H3zm11 0h7v7h-7z',
        'search' => 'm21 21-5-5M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0',
        'link' => 'm10 13 4-4m-6 7-1 1a4 4 0 0 1-6-6l5-5a4 4 0 0 1 6 0m0 2 1-1a4 4 0 0 1 6 6l-5 5a4 4 0 0 1-6 0',
        'shield' => 'm12 2 9 4v6c0 5-9 10-9 10S3 17 3 12V6zm-5 10 3 3 7-7',
        'check' => 'm5 12 4 4L19 6',
        'mail' => 'M3 5h18v14H3zm0 0 9 8 9-8',
        'chat' => 'M21 11.5a9 9 0 0 1-13 8L3 21l1.5-5A9 9 0 1 1 21 11.5Z M8 8c0 4 4 7 7 7l1-2-3-1-1 1-2-2 1-1-1-3Z',
        'pin' => 'M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0ZM15 10a3 3 0 1 1-6 0 3 3 0 0 1 6 0',
        'menu' => 'M4 7h16M4 12h16M4 17h16',
    ];
@endphp
<svg {{ $attributes }} width="{{ $size }}" height="{{ $size }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="{{ $paths[$name] ?? $paths['arrow'] }}"/></svg>
